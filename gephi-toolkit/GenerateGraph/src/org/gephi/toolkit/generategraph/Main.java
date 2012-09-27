/*
 * 
 * 
 * A simple applet to pass a graph file to gephi toolkit for processing.
 * 
 * 
 * 
 */
package org.gephi.toolkit.generategraph;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import org.gephi.data.attributes.api.AttributeColumn;
import org.gephi.data.attributes.api.AttributeController;
import org.gephi.data.attributes.api.AttributeModel;
import org.gephi.filters.api.FilterController;
import org.gephi.graph.api.GraphController;
import org.gephi.graph.api.GraphModel;
import org.gephi.graph.api.UndirectedGraph;
import org.gephi.io.exporter.api.ExportController;
import org.gephi.io.importer.api.Container;
import org.gephi.io.importer.api.EdgeDefault;
import org.gephi.io.importer.api.ImportController;
import org.gephi.io.processor.plugin.DefaultProcessor;
import org.gephi.layout.plugin.force.StepDisplacement;
import org.gephi.layout.plugin.forceAtlas2.ForceAtlas2;
import org.gephi.layout.plugin.forceAtlas2.ForceAtlas2Builder;
import org.gephi.layout.plugin.force.yifanHu.YifanHu;
import org.gephi.layout.plugin.force.yifanHu.YifanHuLayout;
import org.gephi.partition.api.Partition;
import org.gephi.partition.api.PartitionController;
import org.gephi.partition.plugin.NodeColorTransformer;
import org.gephi.preview.api.PreviewController;
import org.gephi.preview.api.PreviewModel;
import org.gephi.preview.api.PreviewProperty;
import org.gephi.project.api.ProjectController;
import org.gephi.project.api.Workspace;
import org.gephi.ranking.api.RankingController;
import org.gephi.statistics.plugin.Modularity;
import org.openide.util.Lookup;

public class Main {

    public static void main(String[] args) {
        
        if(args.length == 0) {
            System.out.println("You must specify a graph file to import as a parameter, AND an output file path. Exiting.");
            System.exit(0);
        }
        
        
        String inputfile = args[0];
        String outputfile = args[1];
        System.out.println("Input File: " + inputfile);
        System.out.println("Output File: " + outputfile);
        
        

            
        //Init a project - and therefore a workspace
        ProjectController pc = Lookup.getDefault().lookup(ProjectController.class);
        pc.newProject();
        Workspace workspace = pc.getCurrentWorkspace();

        //Get models and controllers for this new workspace - will be useful later
        AttributeModel attributeModel = Lookup.getDefault().lookup(AttributeController.class).getModel();
        GraphModel graphModel = Lookup.getDefault().lookup(GraphController.class).getModel();
        PreviewModel model = Lookup.getDefault().lookup(PreviewController.class).getModel();
        ImportController importController = Lookup.getDefault().lookup(ImportController.class);
        FilterController filterController = Lookup.getDefault().lookup(FilterController.class);
        RankingController rankingController = Lookup.getDefault().lookup(RankingController.class);

        //Import file       
        Container container;
        try {
            File file = new File(inputfile);
            InputStream is = new FileInputStream(file);
            container = importController.importFile(file);
            container.getLoader().setEdgeDefault(EdgeDefault.UNDIRECTED);   //Force UNDIRECTED
        } catch (Exception ex) {
            ex.printStackTrace();
            return;
        }

        //Append imported data to GraphAPI
        importController.process(container, new DefaultProcessor(), workspace);

        //See if graph is well imported
        UndirectedGraph graph = graphModel.getUndirectedGraph();
        System.out.println("Nodes: " + graph.getNodeCount());
        System.out.println("Edges: " + graph.getEdgeCount());


        //Do layout
        ForceAtlas2 f2 = new ForceAtlas2Builder().buildLayout();
        f2.setGraphModel(graphModel);
        f2.setScalingRatio(2.0);
        f2.setGravity(15.0);
        f2.setScalingRatio(2.0);
        f2.setThreadsCount(8);
        f2.setAdjustSizes(true);
        f2.initAlgo();
        for (int i = 0; i < 15000 && f2.canAlgo(); i++) {
            f2.goAlgo();
        }
        f2.endAlgo();
        
        
        //Initialise partitionController
        PartitionController partitionController = Lookup.getDefault().lookup(PartitionController.class);

        //Run modularity algorithm - community detection
        Modularity modularity = new Modularity();
        modularity.execute(graphModel, attributeModel);

        //Partition with 'modularity_class', just created by Modularity algorithm
        AttributeColumn modColumn = attributeModel.getNodeTable().getColumn(Modularity.MODULARITY_CLASS);
        Partition p2 = partitionController.buildPartition(modColumn, graph);
        System.out.println(p2.getPartsCount() + " partitions found");
        NodeColorTransformer nodeColorTransformer2 = new NodeColorTransformer();
        nodeColorTransformer2.randomizeColors(p2);
        partitionController.transform(p2, nodeColorTransformer2);       
        
        //Export full graph
        ExportController ec = Lookup.getDefault().lookup(ExportController.class);
        try {
            ec.exportFile(new File(outputfile));
        } catch (IOException ex) {
            ex.printStackTrace();
        }
    }
}
