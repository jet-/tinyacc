<?php
#echo "menu";

?>


<style type="text/css">



body {
/*  font-family: "Trebuchet MS", Helvetica, Arial, sans-serif;
  	font-family: Verdana, Helvetica, Arial, sans-serif;
  	background-color: #e2edff; */
  	background-color: #F6F6F6;
  	line-height: 80%;
  	padding: 15px;

}


table.ref {
	border-collapse: collapse;
	border: 1px solid white;
}

table.ref th {
  	background: #C1FF69;
  	padding: 0.2em;
  	font-size: x-small;
	border: 1px solid white;
}

table.ref td {
  	border: 1px solid white;
	padding: 0.2em;
/*	font-size: x-small;  */
	font-size: small;
}

table.ref tr:hover {
    background-color:#fcf;
}

table.ref caption { 
	caption-side: top; 
        width: auto;
        text-align: left ;
        font-size: medium; 
  	font-family: "Trebuchet MS", Helvetica, Arial, sans-serif;
  	font-weight: bold;
	margin-top: 40px;
}


          
#top {
	position: relative;
}
		
		
	}
	
	#top .menu {
		width:100%;
		height:35px; float: left; position: relative; top: 600px;
	}
	
	#top .menu ul {
		background: url(images/main_menu_middle.png) repeat-x;
		list-style: none;
		position:relative;
		width: 1010px;
		height: 55px;
		margin: 0 auto;
		padding-left:7px;
		

	}
	
		#top ul li {
			background: url(images/element.gif) no-repeat 100% 4px;
			position: relative;
			float: left;
			clear: none;
			height: 35px;
			padding-right:1px;
			
		}
		
		
		
		#top ul li.last {
			background:none;
		
		}
		
			#top ul li a {
				position: relative;
				float: left;
				display: block;			
				background: none;
				height: 26px;
				padding-left:9px;
				padding-right:9px;
				padding-top:9px;
				text-decoration:none;
				font-family:Arial, Helvetica, sans-serif;
				font-size:15px;
				font-weight:bold;
				color: #ffffff;

			}
			

			#top ul li a.active,
			#top ul li a:hover {
				color: #a6a6a6;
			}
			

  </style>





  <div id="top">  
      <div id="menu">
  <div class="menu">
      <ul>
        <li><a href="/"><span>Home</span></a></li>
        <li><a href="entry.php?curr=<?php echo $_GET['curr']; ?>"><span>Add Document</span></a></li>
        <li><a href="rep1.php?curr=<?php echo $_GET['curr']; ?>"><span>General Ledger</span></a></li>
        <li><a href="rep2.php?curr=<?php echo $_GET['curr']; ?>"><span>Acc Statement </span></a></li>
        <li><a href="check.php?curr=<?php echo $_GET['curr']; ?>"><span>Chk acc Dt=Kt</span></a></li>
        <li><a href="search.php?curr=<?php echo $_GET['curr']; ?>"><span>Text search </span></a></li>
        <li><a href="mortgage.php?curr=<?php echo $_GET['curr']; ?>"><span>Mortgage </span></a></li>
        <li class="last"><font size="4" color="yellow"><?php echo $_GET['curr']; ?></font> <span></span></li>
      </ul>
    </div>
</div>
</div>



