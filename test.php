<?php include "includes/head.php" ;?>


<div class="reveal center revealing">
	<div class="circle"></div>
</div>


<style type="text/css">
	.reveal {
		overflow: hidden;
		height: 100vh;
		width: 100vw;
	}

	.circle {
		position: relative;
		background-color: red;
		border-radius: 50%;
		height: 100px;
		width: 100px;
		top: -100;
		animation:
		transition: all 0.34s ease-out;
	}

	.reveal.revealing .circle {
		top: 0;
		transform: scale(50);
	}
</style>

<?php include "includes/head.php" ;?>


<script type="javascript">

</script>