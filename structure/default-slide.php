<style>
/* CSS Document */
@charset "UTF-8";
/*
version improvisée pour les tests...
*/
@font-face { 
    font-family: 'GoetheGothicBold'; 
    src: local('GoetheGothicBold'), local('GoetheGothicBold'), url('../fonts/GoetheGothicBold.ttf') format('truetype'); 
}  

*{
	border:none;
	padding:0px;
	margin:0px; 
}

body{
	overflow:hidden;
}

body,td,th {
	font-family: GoetheGothicBold, Arial, Helvetica, sans-serif !important;
	font-size: 10px;	
}

body, .header, .texte{
	background:rgb(203,02,26);
}

h1 {
	color:#FFF;
	font-weight: normal;
}

#template{
	width:720px;
	height:1210px;
	padding:35px 0px;
	overflow: hidden;
	display:block;
	position:relative;
}

.header h1{
	font-size:65px;
	line-height:65px;
	margin:10px;
	text-transform: uppercase;
	color:#fff;
}

@media screen and (orientation:landscape){

	#template{
		width:1270px;
		height:720px;
		padding:0px 0px;
		position:relative;
	}
	
	.header h1{
		font-size:95px;
		line-height:95px;
	}
	
}


</style>

<div class="header">
<h1><?php echo utf8_decode($this->ecran->nom); ?><br/>
Pas de slide</h1>
</div>