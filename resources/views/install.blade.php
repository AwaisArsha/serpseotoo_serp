
<!doctype html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Installation</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
  <!-- Place favicon.ico in the root directory -->


  <style>
	body {
		font-family: 'Sen', sans-serif;
		font-weight: normal;
		font-style: normal;
		background: #F3F1FA;
	}

	.btn.btn-primary.install-btn {
    box-shadow: #101010 2px 2px 6px, #3e3b3b -2px -2px 6px;
    background: transparent !important;
    padding: 13px 47px;
    border-radius: 5px;
}

	  *, ::after, ::before {
    box-sizing: border-box;
}
	  div {
    display: block;
}
	.install {
		background: #212529;
	}
	.mb-50 {
    	margin-bottom: 50px;
	}

	.mt-50 {
    	margin-top: 50px;
	}

	.content-requirments {
    position: relative;
	}
	
	.justify-content-center {
    	-ms-flex-pack: center!important;
    	justify-content: center!important;
	}

	.d-flex {
    	display: -ms-flexbox!important;
    	display: flex!important;
	}

	.requirments-main-content {
		background: #212529;
		width: 60%;
		margin-left: auto;
		margin-right: auto;
		padding: 53px;
		border-radius: 30px;
		box-shadow: #101010 2px 2px 6px, #3e3b3b -2px -2px 6px;	
	}

	.text-center {
    	text-align: center!important;
	}

	.installer-header h2 {
		text-transform: uppercase;
		font-weight: 600;
		margin-bottom: 15px;
		color: #fdfbfb;
	}

	h2 {
    	font-size: 35px;
		margin-top: 0px;
    	font-style: normal;
	}

	.installer-header p {
		font-size: 18px;
		margin-bottom: 35px;
		color: #b9b7b7;
	}

	p {
		font-size: 14px;
		font-weight: normal;
		line-height: 24px;
		color: #7e7e7e;
		margin-bottom: 15px;
		margin-top: 0;
	}

	table {
    	border-collapse: collapse;
		display: table;
		text-indent: initial;
		border-spacing: 2px;
		border-color: grey;
	}

	.table {
		width: 100%;
		margin-bottom: 1rem;
		color: #212529;
	}

	thead {
		display: table-header-group;
		vertical-align: middle;
		border-color: inherit;
	}

	tr {
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}

	table.table.requirments .thead-light th {
		color: #495057;
		background-color: transparent;
		border-color: #dee2e6;
		border-bottom: none;
		color: #fff;
	}

	.table thead th {
    	vertical-align: bottom;
	}

	tbody {
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}

	table.table.requirments td, .table th {
		padding: 0.75rem;
		vertical-align: top;
		border-top: 1px solid #dee2e6;
		color: #fff;
	}

	td {
    	font-weight: 500;
	}

	.btn {
    border: medium none;

    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 17px;
    font-weight: 500;
    letter-spacing: 1px;
    line-height: 1;
    margin-bottom: 0;
    text-align: center;
    text-transform: capitalize;
    touch-action: manipulation;
    transition: all 0.3s ease 0s;
    vertical-align: middle;
    white-space: nowrap;
}

  </style>
</head>
<body class="install">
          
          <!-- requirments-section-start -->
          <section class="mt-50 mb-50">
            <div class="requirments-section">
              <div class="content-requirments d-flex justify-content-center">
                <div class="requirments-main-content">
                  <div class="installer-header text-center">
                    <h2>Requirments</h2>
                    <p>Please make sure the PHP extentions listed below are installed</p>
                  </div>
                  <table class="table requirments">
                    <thead class="thead-light">
                      <tr>
                        <th scope="col" style="text-align: left;width:50%;">Extensions</th>
                        <th scope="col" style="text-align: left;width:50%;">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>PHP &gt;= 7.4</td>
                        <td>
                                                      <i class="fas fa-check"></i>
                          &nbsp;&nbsp;[Your Version <?php echo phpversion();?>]
                                                    </td>
                      </tr>
                      
                </tbody>
              </table>
                              <a href="/database-setting" class="btn btn-primary install-btn f-right" style="float: right;">Next</a>
                           </div>
         </div>
       </div>
     </section>

     <!-- requirments-section-end -->

   </body>
   </html>
