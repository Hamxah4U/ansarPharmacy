<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SMART Billing</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/icofont/icofont.min.css">
    <link rel="shortcut icon" href="../../img/logo.jpg" type="image/x-icon">
    
     <script src="js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css">
    
    <script src="js/select2.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="js/ckeditor.js"></script>

    

    <style>
        .text-danger {
            font-style: italic;
        }
        .ck-editor__editable {
            min-height: 300px;
        }
        .diary-content p {
            margin-bottom: 6px;
        }
        .diary-content {
            line-height: 1.6;
            font-size: 0.95rem;
        }
        #yearSelector {
            width: auto;
            min-width: 120px;
            cursor: pointer;
        }
        
    </style>

    
<style>
  /* Force Select2 to match Bootstrap 4 form-control elements perfectly */
  .select2-container--bootstrap-4 .select2-selection--single {
    height: calc(1.5em + .75rem + 2px) !important;
    padding: 0.375rem 0.75rem !important;
    /* Standard BS4 padding */
    font-size: 1rem !important;
    font-weight: 400 !important;
    background-color: #fff !important;
    border: 1px solid #ced4da !important;
    border-radius: .25rem !important;
    display: flex;
    align-items: center;
    /* Let flexbox handle vertical centering cleanly */
  }

  /* Fix vertical centering alignment for select dropdown text */
  .select2-container--bootstrap-4 .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    /* Remove the massive forced line-height */
    padding-left: 0 !important;
    color: #495057 !important;
    width: 100%;
  }

  /* Match the drop arrow alignment */
  .select2-container--bootstrap-4 .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    /* Let it scale to the container height */
    top: 0 !important;
    right: .75rem !important;
    display: flex;
    align-items: center;
  }

  /* Match focus shadow color effect from sb-admin template style */
  .select2-container--bootstrap-4.select2-container--focus .select2-selection--single {
    border-color: #bac8f3 !important;
    outline: 0 !important;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25) !important;
  }

  .select2-container {
    display: block !important;
    width: 100% !important;
  }

  .select2-dropdown {
    z-index: 1060 !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
  }
  #wrapper {
    height: 100vh;
    overflow: hidden;
  }

  #accordionSidebar {
    height: 100vh;
    overflow-y: auto;
  }

  #content-wrapper {
    height: 100vh;
    overflow-y: auto;
  }


    /* Custom Elegant Scrollbar Styles */
    ::-webkit-scrollbar {
    width: 8px;  /* Width of vertical scrollbar */
    height: 8px; /* Height of horizontal scrollbar */
    }

    /* Track background */
    ::-webkit-scrollbar-track {
    background: #f1f3f9; 
    border-radius: 4px;
    }
    
    /* Scrollbar Handle/Thumb */
    ::-webkit-scrollbar-thumb {
    background: #bac8f3; /* Soft blue matching your focused select2 borders */
    border-radius: 4px;
    transition: background 0.3s ease;
    }

    /* Scrollbar Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
    background: #4e73df; /* Primary theme color when interacted with */
    }

    /* Smooth scrolling experience for scrollable containers */
    #accordionSidebar, #content-wrapper {
    scroll-behavior: smooth;
    }
</style>


</head>
<body id="page-top">


<?php
    /*         <!DOCTYPE html>
        <html lang="en">
        <head>

            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">

            <title>SMART Billing</title>

            <!-- Custom fonts for this template-->
            <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link
                href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
                rel="stylesheet">

            <!-- Custom styles for this template-->
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
            <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
            <script src="js/sweetalert2.all.min.js"></script>
            <link rel="stylesheet" href="css/icofont/icofont.min.css">
            <link rel="shortcut icon" href="../../img/ansar.png" type="image/x-icon">
            <script src="js/jquery-3.6.0.min.js"></script>

        <link rel="stylesheet" href="css/select2.min.css">
        <link rel="stylesheet" href="css/select2-bootstrap4.min.css">

        <script src="js/select2.min.js"></script>
            <style>
                .text-danger{
                    font-style: italic;
                }
                //  .container-fluid{
                //     max-height: 20px;
                // } 
            </style>
            <!-- select 2 -->
            <!-- Select2 CSS -->
        <link rel="stylesheet" href="css/select2.min.css">

        <!-- jQuery (already needed by your script) -->
        <script src="css/jquery-3.6.0.min.js"></script>

        <!-- Select2 JS -->
        <script src="css/select2.min.js"></script>
        <link rel="stylesheet" href="../../css/select2-bootstrap4.min.css">

        <script src="css/ckeditor.js"></script>

        <script src="js/jquery-3.6.0.min.js"></script>

        <link rel="stylesheet" href="css/select2.min.css">
        <link rel="stylesheet" href="css/select2-bootstrap4.min.css">

        <script src="js/select2.min.js"></script>

        <style>
            .ck-editor__editable {
                min-height: 300px; 
            }

            .diary-content p {
                margin-bottom: 6px;
            }

            .diary-content {
                line-height: 1.6;
                font-size: 0.95rem;
            }

            #yearSelector {
            width: auto;
            min-width: 120px;
            cursor: pointer;
            }

            .container-fluid{
                max-height: 200px;
            }
        </style>


        </head>
        <body id="page-top">




    */
?>