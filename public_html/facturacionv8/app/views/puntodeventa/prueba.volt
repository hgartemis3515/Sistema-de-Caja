<style>
/* Estilo base 
========================*/
@import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  overflow: hidden; /* Evita el scroll en el body */
  font-family: Inter, sans-serif;
}
p, label {
    margin: 0;
}
.btn-primary, .btn.bg-indigo, .label-primary, .btn-default{
    box-shadow: none!important;
}
.color-tertiary {
    color: #64748b;
}
.color-gray-footer{
    color: #474747;
}
.font-weight-bold{ font-weight: 700;}
.form-group {
    margin-bottom: 10px;
    z-index: 0;
}
.d-block{ display: block;}
.d-flex {display: flex;}
.d-flex-column{
    flex-direction: column;
}
.justify-content-center{
    justify-content: center;
}
.justify-content-between{
    justify-content: space-between;
}
.justify-content-around{
    justify-content: space-around;
}
.align-items{ align-items: center; }
.form-control.cliente_numerodocumento  {
    height: 34px !important;
}
.pb-2{ padding-bottom: 2px;}
.w-100{ width: 100%;}
.h-100{height: 100%}
.input-select2 .select2-container--default .selection .select2-selection.select2-selection--single {
    border-bottom-left-radius: 15px;
    border-top-left-radius: 15px;
    border-bottom-right-radius: 15px;
    border-top-right-radius: 15px;
}
.input-group .form-control {
    z-index: 0;
}
/* Materials
=======================*/
.dropwdown-wrap-pt  {
    position: relative;
}
.dropwdown-wrap-pt .profile-user-pt, .dropwdown-wrap-pt .title-drop-main, .navigation-menu-footer .icon-footer-sidebar{
    border: 1px solid #e2e8f0;
    border-radius: .9rem !important;
    padding: 10px 12px;
    font-weight: 700;
    color: #7a81f1;
}
.dropwdown-wrap-pt .title-drop-main{  padding: 5px;border:0}
.dropwdown-wrap-pt .profile-user-pt a, .dropwdown-wrap-pt .title-drop-main a, .content-drop-main a:hover, .box-content-hd a{  color: #7a81f1; }

.dropwdown-wrap-pt .profile-user-pt .letter-profile {
    background: #7a81e973;
    width: 20px;
    height: 20px;
    display: inline-block;
    color: #000000;
    border-radius: 50%;
    text-align: center;
    font-weight: 400;
}

.dropdown-menu-pt {
    border: 1px solid #e2e8f0;
    border-radius: .9rem !important;
    padding: 0;
    -webkit-box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);
    position: absolute;
    z-index: 5;
    background: #fff;
    right: 0;
    margin-top: 5px;
    width: 250px;
    color: #334155;
    visibility: hidden;
    
}
.dropdown-menu-pt.active {   
    visibility: initial;
    -webkit-animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    -webkit-transition: 0.6s;
    transition: 0.6s;
}
.dropdown-menu-pt li {
    list-style: none;
    padding: 10px 15px;
}
.dropdown-menu-pt li:hover {
    background-color: #e2e8f075;
    transition: all .5s;
    cursor: pointer;
}
.dropdown-menu-pt li .bg-default {
    background-color: #e2e8f073;
    color: #64748b;
}
.dropdown-menu-pt li:first-child { 
    border-bottom: 1px solid #e2e8f0;
    justify-content: space-between;
    align-items: center;
}
.border-bottom-dropdown { 
    border-bottom: 1px solid #e2e8f0;
}
.dropdown-menu-pt li a{ color: #334155;}
.dropdown-menu-pt li a i{ color: #7a81f1; margin-right: 5px;}
/* Drop personalizados */
.drop-icon i {
    color: #3f51b5;
    font-size: 40px !important;
}
.text-icon-drop{ gap: 10px;}
.text-icon-drop p > span { font-size: 16px; font-weight: 700;}
@-webkit-keyframes fadeInDown{
      0%
    {
        opacity:0;
        -webkit-transform:translate3d(0,-10%,0);
        transform:translate3d(0,-10%,0)}
        to{
            opacity:1;
            -webkit-transform:none;transform:none}
        }
    @keyframes fadeInDown{
        0%{
            opacity:0;-webkit-transform:translate3d(0,-10%,0);
        transform:translate3d(0,-10%,0)
    }
        to{
            opacity:1;-webkit-transform:none;transform:none
        }
    }
    .fadeInDown{
        -webkit-animation-name:fadeInDown;
        animation-name:fadeInDown
}

/* tooltips */
.tooltip-pt{ 
    position: relative;
}
.box-tooltip-pt {
    border-radius: .9rem !important;
    padding: 0;
    -webkit-box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);
    position: absolute;
    z-index: 999;
    background: #000000d8;
    right: 0;
    margin-top: 5px;
    max-width: 250px;
    color: #FFF;
    visibility: hidden;
    top: 3em;
    width: max-content;
    padding: 5px;
    z-index: 5;
}
.box-tooltip-pt::before {
    content: '';
    width: 0;
    height: 0;
    border: 10px solid transparent;
    border-bottom: 10px solid #000000d8;
    border-top: 0;
    position: absolute;
    top: -9px;
    right: 1em;
}
.box-tooltip-pt.active {   
    visibility: initial;
    -webkit-animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    animation: 500ms ease-in-out 0s normal none 1 running fadeInDown;
    -webkit-transition: 0.6s;
    transition: 0.6s;
}
/* Header
========================*/
.custom-navbar-pt {
    border-bottom: 1px solid #e2e8f0;
    justify-content: space-between;
    align-items: center;
}
.custom-navbar-pt  .wrap-logo-navbar {
    align-items: center;
    height: 100%;
}
.custom-navbar-pt .wrap-logo-navbar .logo-img{ 
    padding: 1em;
    flex: 0 0 100%;
}
.custom-navbar-pt .wrap-logo-navbar .logo-img img {
    max-width: 200px!important;
    min-height: auto;
}
.custom-navbar-pt .wrap-logo-navbar .menu-sidebar-left {
    border-right: 1px solid #e2e8f0;
    padding: 1em;
    height: 100%;
    align-content: center;
    cursor: pointer;
}
.custom-navbar-pt .custom-header-items {
    align-items: center;
    padding: 1em;
}
.custom-navbar-pt i{
    font-size: 20px;
}
.custom-header-items i{
    font-size: 19px;
}
.header-single-item {
    padding: 0 5px;
}
.header-single-item .wifi-online {
    align-items: center;
}

.header-single-item .wifi-online i {
    font-size: 13px;
    color: #fff;
    padding: 3px;
}

/* Cuerpo principal
=========================*/
.container-wrapper {
  display: flex;
  flex-direction: column;
  height: 100vh; 
  overflow: hidden;
}
.container-wrapper img{
    width: 100%;
    height: auto;
    max-width: 100%;
}
.main-body {
    flex: 1 1 auto;
    overflow-y: auto;
    background-color: #f0f0f0;
}


.content-wrap-pt {
    display: flex;
    height: 100%;
    width: 100%;
    background: url(https://arpsystem.com.pe/facturacionv8/img/bg-pattern.png);
}
.content-products {
  overflow-x: hidden; 
}

.content-payment {
  display: flex;
  flex-direction: column;
  background-color: #fff;
  overflow: hidden; 
  max-width: 550px;
}

.content-products, .content-payment {
    flex: 1 1 auto;
    overflow-y: auto;
    min-height: 0; 
}
/* Footer wrapper
======================*/
.footer-wrapper {
    flex-shrink: 0; /* importante: Mantiene el footer fijo en la parte inferior */
    background: #f2f2f2;
    border-top: 1px solid #0003;
    display: flex;
    overflow-x: auto;
}
.droptop-pt {
    position: fixed; 
    display: none;
    width: 150px; 
    border: 1px solid #e2e8f0;
    border-radius: .9rem !important;
    padding: 0;
    -webkit-box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);
    box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .05);
    background-color: #fff;
    z-index: 10;
    transition: 0.6s;
}
.droptop-pt li{
    border-bottom: 1px solid #e2e8f0;
    padding: 10px;
}
.droptop-pt li a{color: #474747;}
.droptop-pt li:hover{ transition: 0.6s; background-color: #efeff0;}
.droptop-pt li i{ 
    margin-right: 10px;
    background: -webkit-linear-gradient(#3f51b5, #7880f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.title-drop-main a:focus {
    color: #3f51b5;
    text-decoration: none;
}
.footer-wrapper  .tab_item_pt.active {
    background: #fff;
    border-top: 2px solid transparent; 
    border-image: linear-gradient(to bottom, #7880f0 0%, #b4b9ff 50%, #3f51b5) 1; 
}
.footer-wrapper .tab_item_pt.active i {
    background: -webkit-linear-gradient(#3f51b5, #7880f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.footer-wrapper .tab_item_pt {
    animation: fadeIn .2s ease;
    -webkit-animation: fadeIn .2s ease;
    -moz-animation: fadeIn .2s ease;
    -o-animation: fadeIn .2s ease;
    -ms-animation: fadeIn .2s ease;
    background: #f2f2f2;
    border-right: 1px solid #0003;
    cursor: pointer;
    max-width: 16rem;
    min-width: 16rem;
    padding: 10px;
    font-weight: 500;
    color: #474747;
    font-size: 1.4rem;
    align-items: center;
}
.tab_item_pt.add-shop-tab {
    max-width: 50px;
    width: 100%;
    border-right: 1px solid #0003;
    min-width: 50px;
    padding: 0;
}
.footer-wrapper .tab_item_pt.add-shop-tab button {
    background: #fff;
    border: 0;
    width: 100%;
    height: 100%;
    font-size: 20px;
}
/* Content product
=======================*/
.search-area-products {
    position: sticky;
    top: 0;
    background: #e1e3ee;
    padding: 2em;
    border-bottom: 1px solid #3f51b5;
    z-index: 1;
}
.content-list-product {
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    margin-top: 2em;
}
.content-list-product .item-product-details{
    border: .2rem solid #0000;
    border-radius: 8px;
    box-shadow: 1px 2px 5px #0000001a;
    cursor: pointer;
    flex: 0 0 22rem;
    margin: 0 2rem 2rem;
    max-width: 22rem;
    display: flex;
    flex-direction: column;
    background: #fff;
    transition: all .5s;
    position: relative;
    overflow: hidden;
}

.content-list-product .item-product-details:hover, .content-list-product .item-product-details.selected-item{
    border: .2rem solid #7981f0;
}
.content-list-product .item-product-details:hover .star-favorite {
    color: #7981f0;
    display: initial;
}
.content-list-product .item-product-details  .img-product {
    border-radius: 8px;
    flex: 0 0 18rem;
    width: 100%;
    overflow: hidden;
    padding: 1rem;
}
/*.content-list-product .item-product-details .img-product img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    -o-object-fit: contain;
    z-index: 1;
    border-radius: 8px;
}*/
.content-list-product .item-product-details .img-product img {
    width: 100%;
    height: auto;
    object-fit: cover;
    -o-object-fit: contain;
    z-index: 1;
    
}
.content-list-product .item-product-details .item-details-name {
    height: 100%;
    padding-top: 1em;
    
}
.content-list-product .item-product-details .item-details-name .item-name {
    font-size: 15px;
    padding: 1rem;
}
.content-list-product .item-product-details .item-details-name .item-price {
    font-size: 16px;
    font-weight: 700;
    color: #7981f0;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 1rem;
    padding: 1rem;
    
}
.content-list-product .item-product-details .item-details-name  .select-product-item {
    margin-top: auto;
    background-color: #7981f0;
    color: #fff;
    font-size: 13px;
    text-align: center;
}
.content-list-product .item-product-details .star-favorite {
    position: absolute;
    left: 0;
    margin: 0 1em;
    color: #7981f0;
    background-color: #fff;
    border: 1px solid #7981f0;
    padding: 5px;
    top: 15px;
    border-radius: 50px;
    border-top-width: 0 !important;
    -webkit-touch-callout: none;
    animation: fadeIn .5s ease;
    -webkit-animation: fadeIn .5s ease;
    -moz-animation: fadeIn .5s ease;
    -o-animation: fadeIn .5s ease;
    -ms-animation: fadeIn .5s ease;
}
.content-list-product .item-product-details .star-favorite.active {
    color: #fff;
    background-color: #7981f0;
    border: 1px solid #fff;
}
.content-list-product .item-product-details .star-favorite i { font-size: 20px;}
/* Sidebar Payment
======================*/
/*Header */
.header-sidebar {
    border-bottom: 1px solid #e2e8f0;
}
.header-sidebar .top-sidebar{
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #e2e8f0;
    padding: 15px;
    align-items: center;
}
.header-sidebar .top-sidebar .invoice_title{
    font-size: 18px;
}
.invoice_options {
    align-items: center;
    gap: 20px;
}
.invoice_options .form-group{
    margin:0;
}
.data-cliente {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 15px;
}
.produc-box-movil{ display: none}
.data-cliente > div:nth-child(1){padding-right: 5px;}
.data-cliente > div:nth-child(1),
.data-cliente > div:nth-child(2) {
  flex: 1 1 50%; /* Cada uno ocupa el 50% de la primera fila */
}

.data-cliente > div:nth-child(3) {
  flex: 1 1 100%; /* Ocupa el 100% de la segunda fila */
  align-items: end;
  gap: 10px;
}

.input-name-client{
    flex: 1 1 70%;
}

/* list de productos
=========================*/
.carr-list-product {
  flex: 1 1 auto; /* Ocupa el espacio restante */
  overflow-y: auto;
  padding: 0 1em;
}
.invoice_item{
   
    border-bottom: 1.7px solid #e2e8f0;
    margin: 0;
    max-width: 100%;
    -webkit-user-select: none;
    user-select: none;
    align-items: center;
    padding: 1em 0;
    flex-wrap: wrap;
}
.invoice_item .name-product-item{
    flex: 1 1 40%;
    -webkit-touch-callout: none;
    animation: fadeIn .2s ease;
    -webkit-animation: fadeIn .2s ease;
    -moz-animation: fadeIn .2s ease;
    -o-animation: fadeIn .2s ease;
    -ms-animation: fadeIn .2s ease;
}

.invoice_item .btn-add-cant {
    font-size: 18px;
    align-self: center;
    flex: 0 0 20%;
    width: 100%;
    justify-content: space-around;
}
.invoice_item  .btn-add-cant .btn {
    padding: 0;
    width: 30px;
    height: 30px;
    line-height: 1;
}
.invoice_item .price-product-item{
    flex: 0 0  30%;
    text-align: right;
}
.invoice_item .price-product-item:hover p{
   display: none;
}
.invoice_item .price-product-item:hover .options-item-product{
    display: flex;
    -webkit-touch-callout: none;
    animation: fadeIn .5s ease;
    -webkit-animation: fadeIn .5s ease;
    -moz-animation: fadeIn .5s ease;
    -o-animation: fadeIn .5s ease;
    -ms-animation: fadeIn .5s ease;
}

.invoice_item .price-product-item .options-item-product {
    display: flex;
    justify-content: end;
    gap: 2em;
    display: none;
}

.invoice_item .price-product-item .options-item-product .opt-fit {
    background-color: #eceef1;
    border-radius: 50%;
    cursor: pointer;
    height: 34px;
    width: 34px;
    text-align: center;
    padding: .5em;
    transition: all .5s;
    border: 0;
}
.invoice_item .price-product-item .options-item-product .opt-fit:hover {
    background-color: #7880f0;
    color: #fff;
    transition: all ease-in .3s;
}

/* Footer Sidebar
================================*/
.footer-sidebar {
    margin-top: auto;
    background-color: #f8f8f8;
    padding: 10px;
    display: flex;
    flex-direction: column;
    border-top: 1px solid #e1e7ee;
    filter: drop-shadow(0 -4px 20px rgba(199, 203, 207, .5));
}
.details-invoice-small {
    display: flex;
    justify-content: space-between;
    padding: 1em 0;
}
.details-invoice-small div:first-child {
    align-items: center;
    color: #8d8d8d;
    display: flex;
    font-size: 14px;
    height: inherit;
    font-weight: 700;
}
.details-invoice-small div:last-child {
    color: #2d3385;
    font-weight: 700;
    font-size: 16px;
}
@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateX(-100px);
  }

  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@-moz-keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateX(-100px);
  }

  100% {
    opacity: 1;
    transform: translateX(0);
  }
}
/* Media Queries
=========================*/
@media (max-width: 600px) {
    .content-products{ display: none;}
    .content-payment{
        flex: 0 0 100%;
    }
    .main-body {
        flex: 1 1 auto;
        overflow-y: auto;
    }

    .footer-wrapper {
        margin-top: auto; 
    }
    .container-wrapper {
        height: 100%;
    }
    .box-name-client{
        display: none;
    }
    /* Sidebar items
    ============================*/
    .btn-add-cant{
        order: 2;
        flex: 1 1 100% !important;
        justify-content: center!important;
        gap: 20px;
        padding-top: 10px;
    }
    .produc-box-movil{
        display: inherit;
    }
    .wifi-online p, .name-user-profile{display: none;}
   
   
    /* Nav superior
    ===========================*/
    
    .logo-img{ display: none;}
    .custom-navbar-pt .custom-header-items {
        border-bottom: 1px solid #e2e8f0;
        width: 100%;
        justify-content: end;
    }
    .dropdown-menu-pt {
        width: auto;
    }
    .box-tooltip-pt{
        width: min-content;
    }
}
@media (min-width: 768px) {
    .content-products {
        flex: 1 1 50%;
    }
}
@media (max-width: 991.98px) {
    .content-products {
        flex: 0 0 50%;
    }
     /* Sidebar items
    ============================*/
    .btn-add-cant{
        order: 2;
        flex: 1 1 100% !important;
        justify-content: center!important;
        gap: 20px;
        padding-top: 10px;
    }
    .wifi-online p{display: none;}
}
 /* Scroll
=================*/
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #d6d6d6;
    border-radius: 5px;
    transition: all .5s ease 0s;
    
}

::-webkit-scrollbar-thumb:hover {
    background: #3f51b5;
}
/* Sidebar menu navegation
=========================*/
.sidebar-menu-pt {
    background: #58595fbf;
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 101;
    visibility: hidden;
    opacity: 0;
    transition: visibility 0.5s ease, opacity 0.5s ease; /* Transición suave de visibilidad y opacidad */
}

.sidebar-menu-pt.open {
    visibility: visible;
    opacity: 1;
}

.sidebar-menu-pt.open .navigation-menu{
    -webkit-touch-callout: none;
    animation: fadeIn .5s ease;
    -webkit-animation: fadeIn .5s ease;
    -moz-animation: fadeIn .5s ease;
    -o-animation: fadeIn .5s ease;
    -ms-animation: fadeIn .5s ease;
    opacity: 1; /* Visible cuando el sidebar tiene la clase 'open' */
}

.sidebar-menu-pt .navigation-menu {
    background: #fff;
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 30rem;
    opacity: 0; /* Oculto por defecto */
    transition: opacity 0.5s ease; /* Transición de opacidad */
}

/*.navigation-menu {
    background: #fff;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: transform .2s ease-in-out;
    width: 34rem !important;
    width: 30rem;
}*/
.navigation-menu-header {
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    gap: .8rem;
    height: 48px;
    width: 100%;
}
.navigation-menu-header a{
    border-right: 1px solid #e2e8f0;
    padding: .4rem 1.6rem;
    height: 100%;
    align-content: center;
    cursor: pointer;
}
.navigation-menu-header a i {  font-size: 20px; color: #334155; }
.navigation-menu-header  h6{  padding: .4rem 1.6rem; font-size: 17px; font-weight: 600;}
.navigation-menu-body{
    margin-bottom: auto;
    overflow-y: auto;
    color: #334155;
}
.navigation-menu-body ul  {   margin: 0; padding: 0;}
.navigation-menu-body ul li {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    list-style: none;
    margin: 0;
    padding: .8rem 0;
}
.navigation-menu-body ul li a {
    align-items: center;
    display: flex;
    gap: .8rem;
    border-radius: .8rem;
    padding: .6rem .8rem;
    transition: all .5s;
    font-size: 14px;
    line-height: 20px;
}
.navigation-menu-body ul li:hover {
    background-color: #e2e8f073;
    color: #0f172a;
}
.navigation-menu-body ul li a{ color: #334155;}
.navigation-menu-body ul li  i{ font-size: 25px; color: #3f51b5;}
.navigation-menu-footer {
    align-items: center;
    border-top: 1px solid #e2e8f0;
    display: flex;
    height: 8rem;
    justify-content: space-between;
    padding: 1.6rem;
}
.navigation-menu-footer p{font-weight: 600; font-size: 16px;}
.navigation-menu-footer .icon-footer-sidebar a{
    color: #3f51b5; 
}
.navigation-menu-footer .icon-footer-sidebar:hover{
    background-color: #7a81f1;
    transition: all .5s;
}
.navigation-menu-footer .icon-footer-sidebar:hover a{
    color: #fff;
}
</style>

<div class="container-wrapper">
    <div class="custom-navbar-pt d-flex">
        <div class="wrap-logo-navbar  d-flex">
            <div class="menu-sidebar-left">
                <i class="ph-list"></i>
            </div>
           <div class="logo-img">
                <img src="https://arpsystem.com.pe/facturacionv8/public/img/logo_facturalaya_461.png" alt="logo header" class="logo-custom-header">
           </div>
           
        </div>
        <div class="custom-header-items d-flex">
            <div class="header-single-item  d-flex position-relative tooltip-pt">
                <div class="wifi-online label bg-success d-flex">
                    <span><i class="ph-wifi-high"></i></span>
                    <p>Disponible</p>
                </div>
                <div class="box-tooltip-pt">
                   <p>Tienes conexión y todas las funciones están disponibles para hacer crecer tu negocio.</p>
                </div>
            </div>
            <div class="header-single-item  tooltip-pt">
                <div class="dropwdown-wrap-pt">
                    <div class="title-drop-main">
                        <a href="JavaScript:void(0)">
                            <i class="icon-loop3"></i>
                        </a>
                    </div>
                    <ul class="dropdown-menu-pt">
                        <li class="text-success font-weight-bold ">Sincronización al día</li>
                        <li><a href="javascript:void(0)" class="align-items d-flex btn_sincro_pt"><i class="ph-check-circle"></i>Sincronizar</a></li>
                        <li><a href="#" class="align-items d-flex"><i class="ph-check-circle"></i>Ventas</a></li>
                        <li><a href="#" class="align-items d-flex"><i class="ph-check-circle"></i>Productos</a></li>
                        <li><a href="#" class="align-items d-flex"><i class="ph-check-circle"></i>Clientes</a></li>
                    </ul>
                </div>
                <div class="box-tooltip-pt">
                    <p>Sincronización</p>
                 </div>
            </div>
           <!-- nuevo btn -->
            <div class="header-single-item  d-flex position-relative tooltip-pt">
                <div class="box-content-hd">
                    <a href="JavaScript:void(0)" class="btn_config_pt_hd">
                        <i class="icon-cog5"></i>
                    </a>
                </div>
              
                <div class="box-tooltip-pt">
                   <p>Configurar punto de Venta</p>
                </div>
            </div>
             <!-- / nuevo btn -->
            <div class="header-single-item  d-flex position-relative tooltip-pt">
                <div class="dropwdown-wrap-pt">
                    <div class="title-drop-main">
                        <a href="JavaScript:void(0)">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </div>
                    <ul class="dropdown-menu-pt">

                        <li><a href="#" class="align-items d-flex"><i class="ph-arrow-square-in"></i>Contactar soporte</a></li>
                        <li><a href="#" class="align-items d-flex"><i class="ph-arrow-square-in"></i>Novedades</a></li>
                        <li><a href="#" class="align-items d-flex"><i class="ph-arrow-square-in"></i>Conoce el punto de venta</a></li>
                    </ul>
                </div>
              
                <div class="box-tooltip-pt">
                   <p>Ayuda</p>
                </div>
            </div>
            <div class="header-single-item  d-flex position-relative tooltip-pt">
                <div class="dropwdown-wrap-pt">
                    <div class="title-drop-main">
                        <a href="JavaScript:void(0)">
                            <i class="ph-clipboard"></i>
                        </a>
                    </div>
                      <ul class="dropdown-menu-pt">
                        <li class="d-flex">Terminal 1</li>
                        <li class="d-flex justify-content-between"><span>Banco débito</span><span class="label bg-default">No seleccionado</span></li>
                        <li class="d-flex justify-content-between"><span>Banco débito</span><span class="label bg-default">No seleccionado</span></li>
                        <li class="d-flex justify-content-between"><span>Banco débito</span><span class="label bg-default">No seleccionado</span></li>
                    </ul>
                </div>
               
                <div class="box-tooltip-pt">
                   <p>Resumen de Terminal</p>
                </div>
            </div>
            <div class="header-single-item">
               
                <div class="dropwdown-wrap-pt">
                    <div class="title-drop-main">
                        <a href="JavaScript:void(0)">
                            <i class="icon-grid2"></i>
                        </a>
                    </div>
                    <ul class="dropdown-menu-pt">
                        <li class="d-flex justify-content-between text-icon-drop">
                            <p class="drop-icon"><i class="ph-calculator"></i></p>
                            <p class="d-flex d-flex-column">
                                <span>Contabilidad</span>
                                <small class="small-text color-tertiary">Contabiliza, factura, crea reportes y más</small>
                            </p>
                        </li>
                        <li class="d-flex justify-content-between text-icon-drop">
                            <p class="drop-icon"><i class="icon-coins"></i></p>
                            <p class="d-flex d-flex-column">
                                <span>Punto de Venta</span>
                                <small class="small-text color-tertiary">Agiliza tu ventas y controla tu efectivo</small>
                            </p>
                        </li>
                        <li class="d-flex justify-content-between text-icon-drop">
                            <p class="drop-icon"><i class="ph-storefront"></i></p>
                            <p class="d-flex d-flex-column">
                                <span>Tienda</span>
                                <small class="small-text color-tertiary">Crea tu primera tienda online en 4 clics</small>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="header-single-item">
                <div class="dropwdown-wrap-pt">
                    <div class="profile-user-pt">
                        <a href="JavaScript:void(0)">
                            <span class="letter-profile"><?php echo substr($user['nombre'], 0, 1);?></span>
                            <span class="name-user-profile"><?php echo $user['nombre'].' '.$user['apellido']?></span>
                        </a>
                    </div>
                    <ul class="dropdown-menu-pt">
                        <li class="d-flex">Facturación electrónica <span class="label bg-default">Desactivada</span></li>
                        <li><a href="/facturacionv8/profile"><i class="icon-user-plus"></i> Mi perfil</a></li>
                        <li><a href="#"><i class="icon-coins"></i>Facturación</a></li>
                        <li><a target="_blank" href="<?php echo $data_empresa['url_soporte']; ?>"><i class="icon-cog5"></i>Soporte</a></li>
                        <li><a href="/facturacionv8/configcompany"><i class="icon-cog5"></i> Configurar empresa</a></li>
                        <li><a href="/facturacionv8/login/logout"><i class="icon-switch2"></i> Cerrar sesión</a></li>
                    </ul>
                </div>
              
            </div>
        </div>
    </div>
    <div class="main-body">
        <div class="content-wrap-pt">
            <div class="content-products">
                <div class="search-area-products">
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <label><i class="icon-cart-add position-left"></i>Aquí puedes buscar y seleccionar tu producto/Servicio!</label>
                            <div class="input-group">
                                <span class="input-group-btn sr-group">
                                    <button class="btn bg-indigo legitRipple" type="button">
                                        <i class="icon-search4"></i>
                                    </button>
                                    <span  class="btn btn-default legitRipple btn-icon-bar"><i class="icon-barcode2"></i></span>
                                </span>
                                <input type="text" name="txt_busqueda_productos" id="txt_busqueda_productos" class="form-control" placeholder="Buscar productos">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vm_agregar_articulo">
                                        <i class="icon-plus-circle2 mr-2"></i> <span class="nuevo-producto">Nuevo</span>
                                        </button>
                                    
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-list-product d-flex">
                    <div class="item-product-details selected-item">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media-B6TdITzb.png" alt="">
                        </div>
                        <div class="item-details-name d-flex d-flex-column">
                            <p class="item-price">S/9.564,46</p>
                            <p class="item-name">10544Sist. de aire acondicionado montaje vert. en panel o puerta de tablero - 640w35347 LEGRAND</p>
                            <p class="select-product-item">1 seleccionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite active"><i class="icon-star-full2"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media4-DcNzu6e7.png" alt="">
                        </div>
                        <div class="item-details-name d-flex d-flex-column">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                  
                    
                    <div class="item-product-details selected-item">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media4-DcNzu6e7.png" alt="">
                        </div>
                        <div class="item-details-name d-flex d-flex-column">
                            <p class="item-price">S/9.564,46</p>
                            <p class="item-name">10544Sist. Lorem ipsum dolor sit amet consectetur adipisicing elit</p>
                            <p class="select-product-item">10 seleccionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media4-DcNzu6e7.png" alt="">
                        </div>
                        <div class="item-details-name">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite active"><i class="icon-star-full2"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media-B6TdITzb.png" alt="">
                        </div>
                        <div class="item-details-name">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                    <div class="item-product-details">
                        <div class="star-favorite"><i class="icon-star-empty3"></i></div>
                        <div class="img-product">
                            <img src="https://pixio.scriptlelo.com/vue/demo/assets/banner-media-B6TdITzb.png" alt="">
                        </div>
                        <div class="item-details-name">
                            <p class="item-price">S/9.564</p>
                            <p class="item-name">10544Sist. de aire acondicionado</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-payment">
                <div class="header-sidebar">
                    <div class="top-sidebar">
                        <div class="invoice_title">
                            <p>Ventas</p>
                        </div>
                        <div class="invoice_options d-flex">
                            <div class="form-group input-select2">
                               
                                <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select2" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
                                    <option value="0">Sin.Doc.</option>
                                    <option value="1">DNI</option>
                                    <option value="6">RUC</option>
                                    <option value="4">CarnetExtranj.</option>
                                </select>
                            </div>
                            <div class="options-buy">
                                <ul class="icons-list text-right">
                                    <li class="dropdown">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">   
                                            <i class="icon-paragraph-justify3"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a onclick=""  href="javascript:void(0)">Moneda</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="data-cliente">
                        <div class="form-group input-select2">
                            <label  class="label-form">
                                <i class="icon-user mr-2"></i> 
                                Tipo de Doc 
                            </label>
                            <select title="Selecciona el tipo de documento" data-placeholder="Selecciona el Tipo de Doc." class="cliente_tipo_docidentidad select2" name="cliente_tipo_docidentidad" id="cliente_tipo_docidentidad">
                                <option value="0">Sin.Doc.</option>
                                <option value="1">DNI</option>
                                <option value="6">RUC</option>
                                <option value="4">CarnetExtranj.</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label  class="label-form">
                                <i class="icon-user mr-2"></i>
                                <span class="type_id" id="type_id">Doc. de Identidad</span> 
                            
                            </label>
                            <div class="input-group">
                                <input type="number" inputmode="numeric" pattern="[0-9]*" title="Número de Documento" name="cliente_numerodocumento" id="cliente_numerodocumento" placeholder="Número de documento Aquí!" class="form-control cliente_numerodocumento pt_type_id" required>
                                <span class="input-group-btn">
                                    <button class="btn bg-indigo btn-icon legitRipple search_document" type="button">
                                        <i class="icon-search4" id="icon_search_document"></i>
                                        <i class="icon-spinner10 spinner position-left" style="display: none;" id="icon_searching_document"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group d-flex box-name-client">
                            <div class="input-name-client label-form">
                                <i class="icon-users2 mr-2"></i> 
                                Nombre
                                <input type="text" class="form-control form-control-sm" name="cliente_nombre" id="cliente_nombre" placeholder="Nombre"> 
                            </div>
                            <div class="btn-add-client pb-2">
                                <button class="btn bg-indigo dropdown-toggle legitRipple">Nuevo </button>
                            </div>
                           
                        </div>
                        <div class="produc-box-movil mb-2  mt-2 d-flex d-flex-column w-100">
                            <label><i class="icon-cart-add position-left"></i>Busca tu producto/Servicio</label>
                            <div class="input-group">
                                <span class="input-group-btn sr-group">
                                    <button class="btn bg-indigo legitRipple" type="button">
                                        <i class="icon-search4"></i>
                                    </button>
                                    <span  class="btn btn-default legitRipple btn-icon-bar"><i class="icon-barcode2"></i></span>
                                </span>
                                <input type="text" name="txt_busqueda_productos" id="txt_busqueda_productos" class="form-control" placeholder="Buscar productos">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vm_agregar_articulo">
                                        <i class="icon-plus-circle2 mr-2"></i> <span class="nuevo-producto">Nuevo</span>
                                    </button>
                                
                                </span>
                            </div>
                        </div>
    
                    </div>
                </div>
               
                <div class="carr-list-product">
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">10536Tablero </p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="invoice_item d-flex">
                        <div class="name-product-item">
                            <p class="font-weight-700">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Rerum voluptates dolorum, tempore delectus odit earum sint labore esse</p>
                        </div>
                        <div class="btn-add-cant d-flex">
                            <button class="btn">-</button>
                            <span>1</span>
                            <button class="btn">+</button>
                        </div>
                        <div class="price-product-item">
                            <p>S/ 2.000,00</p>
                            <div class="options-item-product">
                                <button class="opt-fit"><i class="icon-pencil3"></i></button>
                                <button class="opt-fit"><i class="icon-bin"></i></button>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="footer-sidebar">
                    <div class="invoice-payment-total">
                        <button class="btn bg-indigo d-block w-100"> 
                            <span>Vender</span>
                            <span>S/ 4,00.00</span>
                        </button>
                    </div>
                    <div class="details-invoice-small">
                        <div>3 Productos</div>
                        <div>Cancelar</div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

   <div class="footer-wrapper">
        <div class="tab_item_pt active d-flex justify-content-between">
            <span><i class="icon-cart"></i> Venta 1</span>
            <div class="dropwtop-wrap-pt drop-top">
                <div class="content-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
                <li class="d-flex"><a href="javascript:void(0)" class="btn_config_venta"><i class="icon-cog5"></i> Configurar</a></li>
            </ul>
        </div>
        <div class="tab_item_pt  d-flex justify-content-between">
            <i class="icon-cart"></i> Venta 2
            <div class="dropwtop-wrap-pt drop-top">
                <div class="content-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
                <li class="d-flex"><a href="javascript:void(0)" class="btn_config_venta"><i class="icon-cog5"></i> Configurar</a></li>
            </ul>
        </div>
        <div class="tab_item_pt  d-flex justify-content-between">
            <i class="icon-cart"></i> Venta 3
            <div class="dropwtop-wrap-pt drop-top">
                <div class="content-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
                <li class="d-flex"><a href="javascript:void(0)" class="btn_config_venta"><i class="icon-cog5"></i> Configurar</a></li>
            </ul>
        </div>
        <div class="tab_item_pt  d-flex justify-content-between">
            <i class="icon-cart"></i> Venta 4
            <div class="dropwtop-wrap-pt drop-top">
                <div class="content-drop-main">
                    <a href="JavaScript:void(0)" class="color-gray-footer">
                        <i class="icon-more"></i>
                    </a>
                </div>
            </div>
            <ul class="droptop-pt">
                <li class="d-flex"><i class="icon-pencil3"></i> Renombrar</li>
                <li class="d-flex"><i class="icon-bin"></i> Eliminar</li>
                <li class="d-flex"><a href="javascript:void(0)" class="btn_config_venta"><i class="icon-cog5"></i> Configurar</a></li>
            </ul>
        </div>
        <div class="tab_item_pt add-shop-tab">
            <button>+</button>
        </div>
    </div>

</div>

<div class="sidebar-menu-pt">
    <div class="navigation-menu">
        <div class="navigation-menu-header">
            <a href="javascript:void(0)" class="sidebar-header-button"><i class="ph-list"></i></a>
            <h6>Punto de Venta</h6>
        </div>
        <div class="navigation-menu-body">
            <ul>
                <li><a href="#"><i class="ph-house"></i>Vender</a></li>
                <li><a href="#"><i class="ph-house"></i>Ingresos</a></li>
                <li><a href="#"><i class="ph-house"></i>Gestion en efectivo</a></li>
                <li><a href="#"><i class="ph-house"></i>Contactos</a></li>
                <li><a href="#"><i class="ph-house"></i>Inventario</a></li>
                <li><a href="#"><i class="ph-house"></i>Configuraciones</a></li>
                <li><a href="#">Mi suscripción</a></li>
                <li><a href="#">Portal de clientes</a></li>
                <li><a href="#">Atajos del teclado</a></li>
            </ul>
        </div>
        <div class="navigation-menu-footer">
            <div class="d-flex d-flex-column">
                <p>Última sincronización</p>
                <small xlass="color-tertiary">05/11/2024 8:34 pm</small>
            </div>
          
            <div class="icon-footer-sidebar">
                <a href="#"><i class="icon-loop3"></i></a>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="vm_config_venta" class="modal fade vm_config_venta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="vm_config_post_titulo">Configuración del Punto de Venta</h5>
            </div>

            <div class="modal-body"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="vm_config_post" class="modal fade vm_config_post" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="vm_config_post_titulo">Configuración del Punto de Venta</h5>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Selecciona el Almacén/Sucursal:</label>
                    <div class="col-sm-6">
                        <select name="pos_opt_global_sucursal" id="pos_opt_global_sucursal" class="form-control">
                            <?php foreach ($lista_sucursales as $sucursal): ?>
                                <option 
                                    data-totalproductos="<?= htmlspecialchars($sucursal->cantidad_productos, ENT_QUOTES, 'UTF-8'); ?>" 
                                    value="<?= htmlspecialchars($sucursal->idsucursal, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?= htmlspecialchars($sucursal->idsucursal.'.- '.$sucursal->nombre.' (NumProds: '.$sucursal->cantidad_productos.') ', ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button id="pos_opt_global_btn_sincronizarsucursal" class="btn btn-primary btn-sm">Sincronizar</button>
                    </div>
                    <div class="col-sm-12" id="contenedor_aviso_sincronizacion" style="display:none;">
                        <div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered" id="aviso_sincronizacion_sucursal">
                            
                        </div>
                    </div>
                    <div class="col-sm-12" id="contentenedor_progressbar_sincronizar_sucursal" style="display:none">
                        <div class="progress-bar bg-teal" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0% Completo</div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-switch">
                                <label class="label-form">
                                    <input name="descargar_tablas_configuracion" id="descargar_tablas_configuracion" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_pos_opt_global" data-size="mini" >
                                    ¿Desea descargar las tablas de configuración?
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12" id="contentenedor_progressbar_descargar_tablas" style="display:none">
                            <div class="progress-bar bg-teal" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0% Completo</div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<div id="vm_config_punto_venta" class="modal fade vm_config_punto_venta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="vm_config_post_titulo">Configuración del Punto de Venta</h5>
            </div>

            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Tipo de Cambio (Actual: <?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>):</label>
                    <div class="col-sm-4">
                        <input name="pos_opt_global_tipocambio" id="pos_opt_global_tipocambio" type="text" class="form-control" value="<?php echo $data_tipo_cambio['tipo_cambio_venta']; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">IGV:</label>
                    <div class="col-sm-2">
                        <select name="pos_opt_global_factorigv" id="pos_opt_global_factorigv" class="form-control">
                            <option>18%</option>
                            <option>10%</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-switch">
                                <label class="label-form">
                                    <input name="pos_opt_global_enviar_email" id="pos_opt_global_enviar_email" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_pos_opt_global" data-size="mini" >
                                    ¿Deseas Enviar Automáticamente un Email al Cliente con su Compra?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-switch">
                                <label class="label-form">
                                    <input name="pos_opt_global_habilitar_oc" id="pos_opt_global_habilitar_oc" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_pos_opt_global" data-size="mini" >
                                    ¿Habilitar el Número de Orden en el CPE?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-switch">
                                <label class="label-form">
                                    <input name="pos_opt_global_habilitar_numplaca" id="pos_opt_global_habilitar_numplaca" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_pos_opt_global" data-size="mini" >
                                    ¿Habilitar el Número de Placa en el CPE?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-switch">
                                <label class="label-form">
                                    <input name="pos_opt_global_habilitar_numguia" id="pos_opt_global_habilitar_numguia" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_pos_opt_global" data-size="mini" >
                                    ¿Habilitar Guía de Remisión en el CPE?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-switch">
                                <label class="label-form">
                                    <input name="pos_opt_global_habilitar_asignarventa" id="pos_opt_global_habilitar_asignarventa" type="checkbox" data-on-text="Si" data-off-text="No" class="switch_pos_opt_global" data-size="mini" >
                                    ¿Habilitar la opción de Asignar Venta?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
