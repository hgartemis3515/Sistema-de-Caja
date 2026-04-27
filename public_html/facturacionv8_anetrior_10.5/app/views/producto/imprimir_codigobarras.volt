<style>
    body {
        width: 12.75in  !important;
        margin: 0in !important;
        }

	.label1 {
        /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
        width: 2.0in  !important; /* plus .6 inches from padding */
        margin-right: 0in  !important;  /*the gutter */
        float: left;

        text-align: center  !important;
        overflow: hidden  !important;

        outline: 0px dotted; /* outline doesn't occupy space like border does */
    }

	.label2 {
        /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
        width: 2.0in  !important; /* plus .6 inches from padding */
        margin-right: 0in  !important;  /*the gutter */
        float: left;

        text-align: center  !important;
        overflow: hidden  !important;

        outline: 0px dotted; /* outline doesn't occupy space like border does */
    }

	.label3 {
        /* Avery 5160 labels -- CSS and HTML by MM at Boulder Information Services */
        width: 2.025in  !important; /* plus .6 inches from padding */
        margin-right: 0in  !important;  /*the gutter */
        float: left;

        text-align: center  !important;
        overflow: hidden  !important;

        outline: 0px dotted; /* outline doesn't occupy space like border does */
    }
	</style>

<input type="hidden" id="codigo_producto" value="<?php echo $codigo_producto; ?>" >

<div class="label1">
	<div>
		<span><?php echo $l1; ?></span><br />
		<span><?php echo $l2; ?></span><br />
		<span><?php echo $l3; ?></span>
	</div>
	<svg id="barcode1" style="max-width: 100%;  display: inline-block;"></svg>
</div>

<div class="label2">
	<div>
		<span><?php echo $l1; ?></span><br />
		<span><?php echo $l2; ?></span><br />
		<span><?php echo $l3; ?></span>
	</div>
	<svg id="barcode2" style="max-width: 100%;  display: inline-block;"></svg>
</div>

<div class="label3">
	<div>
		<span><?php echo $l1; ?></span><br />
		<span><?php echo $l2; ?></span><br />
		<span><?php echo $l3; ?></span>
	</div>
	<svg id="barcode3" style="max-width: 100%;  display: inline-block;"></svg>
</div>