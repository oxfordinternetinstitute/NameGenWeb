<html>
<head>
<script src="../js/sigma.min.js"></script>
<script src="../js/jquery-1.7.1.min.js"></script>
<style>
.sigma {
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color:#000;
  }

.node-info-popup {
	font-size:11px;
}

.node-info-popup ul {
	list-style:none;
	margin:0;
	padding:0;
}


body
{
background-color:#000;
font-family: "Verdana", serif;
color:#EEEEEE;
}
a
{
color:#EEEEEE;
}
</style>
<title>Test</title>
</head>
<body>
<div id="content">
    <div id="sigma-canvas" class="sigma">
    </div>
    
</div>

<script src="../js/jquery-1.7.1.min.js"></script><script src="../js/sigma.parseGexf.js"></script>
<script type="text/javascript">function init() {
  /**
   * First, let's instanciate sigma.js :
   */
  var sigInst = sigma.init($('#sigma-canvas')[0]).drawingProperties({
    defaultLabelColor: '#fff',
    defaultLabelSize: 14,
    defaultLabelBGColor: '#fff',
    defaultLabelHoverColor: '#000',
    labelThreshold: 7,

    defaultEdgeType: 'curve'
}).graphProperties({
  minNodeSize: 0.3,
  maxNodeSize: 4,
  minEdgeSize: 1,
  maxEdgeSize: 1
}).mouseProperties({
  maxRatio: 32
});


  // (requires "sigma.parseGexf.js" to be executed)
  sigInst.parseGexf('../output/<?php echo $_SERVER['QUERY_STRING']; ?>');

  /**
   * Now, here is the code that shows the popup :
   */
  (function(){
    var popUp;

    // This function is used to generate the attributes list from the node attributes.
    // Since the graph comes from GEXF, the attibutes look like:
    // [
    //   { attr: 'Lorem', val: '42' },
    //   { attr: 'Ipsum', val: 'dolores' },
    //   ...
    //   { attr: 'Sit',   val: 'amet' }
    // ]
    function attributesToString(attr) {
      return '<ul>' +
        attr.map(function(o){
          return '<li>' + o.attr + ' : ' + o.val + '</li>';
        }).join('') +
        '</ul>';
    }

    function showNodeInfo(event) {
      popUp && popUp.remove();

      var node;
      sigInst.iterNodes(function(n){
        node = n;
      },[event.content[0]]);

      popUp = $(
        '<div class="node-info-popup"></div>'
      ).append(
        // The GEXF parser stores all the attributes in an array named
        // 'attributes'. And since sigma.js does not recognize the key
        // 'attributes' (unlike the keys 'label', 'color', 'size' etc),
        // it stores it in the node 'attr' object :
        attributesToString( node['attr']['attributes'] )
      ).attr(
        'id',
        'node-info'+sigInst.getID()
      ).css({
        'display': 'inline-block',
		'width' : '300px',
		'overflow' : 'hidden',
        'border-radius': 3,
        'padding': 15,
        'background-color': 'rgba(000,000,000,0.8)',
        'color': '#fff',
        'box-shadow': '0 0 4px #666',
        'position': 'absolute',
        'left': node.displayX,
        'top': node.displayY+25
      });

      $('ul',popUp).css('margin','0 0 0 20px');

      $('#sigma-canvas').append(popUp);
    }

    function hideNodeInfo(event) {
      popUp && popUp.remove();
      popUp = false;
    }

  // Bind events :
  var greyColor = '#666';
  sigInst.bind('overnodes',function(event){
    var nodes = event.content;
    var neighbors = {};
    sigInst.iterEdges(function(e){
      if(nodes.indexOf(e.source)<0 && nodes.indexOf(e.target)<0){
        if(!e.attr['grey']){
          e.attr['true_color'] = e.color;
          e.color = greyColor;
          e.attr['grey'] = 1;
        }
      }else{
        e.color = e.attr['grey'] ? e.attr['true_color'] : e.color;
        e.attr['grey'] = 0;

        neighbors[e.source] = 1;
        neighbors[e.target] = 1;
      }
    }).iterNodes(function(n){
      if(!neighbors[n.id]){
        if(!n.attr['grey']){
          n.attr['true_color'] = n.color;
          n.color = greyColor;
          n.attr['grey'] = 1;
        }
      }else{
        n.color = n.attr['grey'] ? n.attr['true_color'] : n.color;
        n.attr['grey'] = 0;
      }
    }).draw(2,2,2);
  }).bind('outnodes',function(){
    sigInst.iterEdges(function(e){
      e.color = e.attr['grey'] ? e.attr['true_color'] : e.color;
      e.attr['grey'] = 0;
    }).iterNodes(function(n){
      n.color = n.attr['grey'] ? n.attr['true_color'] : n.color;
      n.attr['grey'] = 0;
    }).draw(2,2,2);
  });

	sigInst.position(0,0,1).draw();
    sigInst.bind('overnodes',showNodeInfo).bind('outnodes',hideNodeInfo).draw();
  })();
}

if (document.addEventListener) {
  document.addEventListener('DOMContentLoaded', init, false);
} else {
  window.onload = init;
}
</script>
</body>
</html>