<style>
.alert[class*=alert-styled-].alert-danger:after, .alert[class*=alert-styled-][class*=bg-danger]:after {
	content: '\e9bd';
}

h4, .h4, h5, .h5, h6, .h6 {
	margin-top: 0px;
	margin-bottom: 0px;
}
.mr-2{
	margin-right: .20em;
}
.mb-60{
	margin-bottom: 60px!important;
}
.pd-2{
	padding: 4em;
} 
.row {
	display: -ms-flexbox;
	display: flex;
	-ms-flex-wrap: wrap;
	flex-wrap: wrap;
	margin-right: -15px;
	margin-left: -15px;
}
.justify-content-center {
	-ms-flex-pack: center!important;
	justify-content: center!important;
}

.pricingTable > .pricingTable-title {
	text-align: center;
	color: #6e768d;
	font-size: 3em;
	font-size: 300%;
	margin-bottom: 20px;
	letter-spacing: 0.04em;
}
.pricingTable > .pricingTable-subtitle {
	text-align: center;
	color: #b4bdc6;
	font-size: 1.8em;
	letter-spacing: 0.04em;
	margin-bottom: 60px;
}
@media screen and (max-width: 480px) {
	.pricingTable > .pricingTable-subtitle {
	margin-bottom: 30px;
	}
	.title-item{
	display: block;
	margin-left: 30px;
	}
}
.pricingTable-firstTable {
	list-style: none;
	padding-left: 2em;
	padding-right: 2em;
	text-align: center;
}
.pricingTable-firstTable_table {
	vertical-align: middle;
	width: 31%;
	background-color: #ffffff;
	display: inline-block;
	padding: 0px 30px 40px;
	text-align: center;
	max-width: 320px;
	transition: all 0.3s ease;
	border-radius: 5px;
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table {
	display: block;
	width: 90%;
	margin: 0 auto;
	max-width: 90%;
	margin-bottom: 20px;
	padding: 10px;
	padding-left: 20px;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table > * {
	display: inline-block;
	vertical-align: middle;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table > * {
	display: block;
	float: none;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table:after {
	display: table;
	content: '';
	clear: both;
	}
}
.pricingTable-firstTable_table:hover {
	-webkit-transform: scale(1.08);
			transform: scale(1.08);
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table:hover {
	-webkit-transform: none;
			transform: none;
	}
}
.pricingTable-firstTable_table:not(:last-of-type) {
	margin-right: 3.5%;
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table:not(:last-of-type) {
	margin-right: auto;
	}
}
.pricingTable-firstTable_table:nth-of-type(2) {
	position: relative;
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table:nth-of-type(2) h1 {
	padding-top: 8%;
	}
}
.pricingTable-firstTable_table:nth-of-type(2):before {
	content: 'Popular';
	position: absolute;
	color: white;
	display: block;
	background-color: #3bbdee;
	text-align: center;
	right: 15px;
	top: -25px;
	height: 75px;
	width: 75px;
	border-radius: 50%;
	box-sizing: border-box;
	font-size: 8.5px;
	padding-top: 22px;
	text-transform: uppercase;
	letter-spacing: 0.13em;
	transition: all 0.5s ease;
}
@media screen and (max-width: 988px) {
	.pricingTable-firstTable_table:nth-of-type(2):before {
	font-size: 0.6em;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table:nth-of-type(2):before {
	left: 10px;
	width: 45px;
	height: 45px;
	top: -10px;
	padding-top: 13px;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table:nth-of-type(2):before {
	font-size: 7px;
	}
}
.pricingTable-firstTable_table:nth-of-type(2):hover:before {
	-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
}
.pricingTable-firstTable_table__header {
	font-size: 28px;
	padding: 40px 0px;
	border-bottom: 2px solid #ebedec;
	letter-spacing: 0.03em;
}
@media screen and (max-width: 1068px) {
	.pricingTable-firstTable_table__header {
	font-size: 1.45em;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__header {
	padding: 0px;
	border-bottom: none;
	float: left;
	width: 33%;
	padding-top: 3%;
	padding-bottom: 2%;
	}
}
@media screen and (max-width: 610px) {
	.pricingTable-firstTable_table__header {
	font-size: 1.3em;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table__header {
	float: none;
	width: 100%;
	font-size: 1.8em;
	margin-bottom: 30px;
	padding-bottom: 20px;
	border-bottom: 2px solid #ebedec;
	}
}
.pricingTable-firstTable_table__pricing {
	font-size: 55px;
	padding: 30px 0px;
	border-bottom: 2px solid #ebedec;
	line-height: 0.7;
}
@media screen and (max-width: 1068px) {
	.pricingTable-firstTable_table__pricing {
	font-size: 2.8em;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__pricing {
	border-bottom: none;
	padding: 0;
	float: left;
	clear: left;
	width: 33%;
	}
}
@media screen and (max-width: 610px) {
	.pricingTable-firstTable_table__pricing {
	font-size: 2.4em;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table__pricing {
	float: none;
	width: 100%;
	font-size: 3em;
	margin-bottom: 10px;
	}
}
.pricingTable-firstTable_table__pricing span:first-of-type {
	font-size: 0.35em;
	vertical-align: top;
	letter-spacing: 0.15em;
}
@media screen and (max-width: 1068px) {
	.pricingTable-firstTable_table__pricing span:first-of-type {
	font-size: 0.3em;
	}
}
.pricingTable-firstTable_table__pricing span:last-of-type {
	vertical-align: bottom;
	font-size: 0.30em;
	letter-spacing: 0.04em;
	padding-left: 0.2em;
}
@media screen and (max-width: 1068px) {
	.pricingTable-firstTable_table__pricing span:last-of-type {
	font-size: 0.25em;
	}
}
.pricingTable-firstTable_table__options {
	list-style: none;
	padding: 15px;
	font-size: 16px;
	border-bottom: 2px solid #ebedec;
	text-align: left;
	margin-bottom: 2em;
}
@media screen and (max-width: 1068px) {
	.pricingTable-firstTable_table__options {
	font-size: 0.85em;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__options {
	border-bottom: none;
	padding: 0;
	margin-right: 10%;
	}
}
@media screen and (max-width: 610px) {
	.pricingTable-firstTable_table__options {
	font-size: 0.7em;
	margin-right: -8%;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table__options {
	font-size: 1.3em;
	margin-right: none;
	margin-top: 30px;
	margin-bottom: 30px;
	padding-bottom: 20px;
	border-bottom: 2px solid #ebedec;
	}
}
.pricingTable-firstTable_table__options > li {
	padding: 8px 0px;
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__options > li {
	text-align: left;
	}

}
@media screen and (max-width: 610px) {
	.pricingTable-firstTable_table__options > li {
	padding: 5px 0;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table__options > li {
	text-align: left;
	padding-left: 1em;
	}
}
.pricingTable-firstTable_table__options > li:before {
	content: '✓';
	display: inline-block;
	margin-right: 15px;
	color: white;
	background-color: #74ce6a;
	border-radius: 50%;
	width: 15px;
	height: 15px;
	font-size: 9px;
	padding: 2px;
	text-align: center;
}

.pricingTable-firstTable_table__getstart {
	color: white;
	background-color: #3F51B5;
	margin-top: 30px;
	border-radius: 5px;
	cursor: pointer;
	padding: 15px;
	box-shadow: 0px 3px 0px 0px #2c3b8e;
	letter-spacing: 0.07em;
	transition: all 0.4s ease;
}
.pricingTable-firstTable_table__getstart:hover, .pricingTable-firstTable_table__getstart:active, .pricingTable-firstTable_table__getstart:focus{
	color: white;
}
@media screen and (max-width: 1068px) {
	.pricingTable-firstTable_table__getstart {
	font-size: 11px;
	}
	.pricingTable-firstTable_table__options > li:before {
	content: '✓';
	display: inline-block;
	margin-right: 5px;
	color: white;
	background-color: #74ce6a;
	border-radius: 50%;
	width: 15px;
	height: 15px;
	font-size: 9px;
	padding: 2px;
	text-align: center;
	}
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__getstart {
	margin-top: 0;
	}
	.title_header{
	float: none;
	width: 100%;
	}
}
@media screen and (max-width: 610px) {
	.pricingTable-firstTable_table__getstart {
	font-size: 0.9em;
	padding: 10px;
	}
}
@media screen and (max-width: 480px) {
	.pricingTable-firstTable_table__getstart {
	font-size: 1em;
	/* width: 50%; */
	margin: 10px auto;
	}
}
.pricingTable-firstTable_table__getstart:hover {
	-webkit-transform: translateY(-10px);
			transform: translateY(-10px);
	box-shadow: 0px 40px 29px -19px rgba(44, 59, 142, 0.815);
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__getstart:hover {
	-webkit-transform: none;
			transform: none;
	box-shadow: none;
	}

}
.pricingTable-firstTable_table__getstart:active {
	box-shadow: inset 0 0 10px 1px #3F51B5, 0px 40px 29px -19px rgba(44, 59, 142, 0.815);
	-webkit-transform: scale(0.95) translateY(-9px);
			transform: scale(0.95) translateY(-9px);
}
@media screen and (max-width: 767px) {
	.pricingTable-firstTable_table__getstart:active {
	-webkit-transform: scale(0.95) translateY(0);
			transform: scale(0.95) translateY(0);
	box-shadow: none;
	}
}
@media screen and (min-width: 2000px){
	.pd-2 {
	padding: 4em 20em 4em 20em;
	}
}
</style>
<!-- Page header -->
<div class="page-header">
	<div class="page-header-content" style="max-width: 1120px;margin: 0 auto;">
		<?php echo $html_suscripcion; ?>
	</div>
</div>