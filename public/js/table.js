$(function(){
  $(".fold-table tr.view").on("click", function(){
    $(this).toggleClass("open").next(".fold").toggleClass("open");
  });
});

/* function format (d) {
// `d` is the original data object for the row
return 'I can see the CHILD ROW!!!';
 } */
/* $(document).ready(function() {
		var tableOuter = $('#historyTableOuter').DataTable({
		    columns: [
        {
            className:      'details-control',
            orderable:      false,
            data:           null,
            defaultContent: ''
        },
        { "data": "Job Name" },
        { "data": "Description" },
        { "data": "	Submitted at" },
        { "data": "Duration" }
    ],
    "order": [[1, 'asc']]
		});

			$('#historyTableOuter tbody').on('click', 'td.details-control', function () {
				var tr = $(this).closest('tr');
				var row = tableOuter.row( tr );
 
				if ( row.child.isShown() ) {
					row.child.hide();
					tr.removeClass('shown');
				}
				else {
					row.child( format(row.data()) ).show();
					tr.addClass('shown');
				}
			});
} ); */

/* $(document).ready(function() {
		function searchByColumn(tableOuter){
			var defaultSearch = 0 // Job Name
			
			$(document).on('change', '#search-column', function() {
				defaultSearch = this.value;
			});
			
			$(document).on('keyup', '#search-by-column', function(){
				tableOuter.search('').columns().search('').draw();
				tableOuter.column(defaultSearch).search(this.value).draw();
			});
		}
		
    var tableOuter = $('#historyTableOuter').DataTable( {
			"searching": true,
			"fixedHeader": true,
			"dom": '<"filterSearchOuter">rtip'
		});
		$("div.filterSearchOuter").html('<div class="row">\
											<div class="col-md-5">\
											<select class="form-control" id="search-column">\
												<option value="0">Job name</option>\
												<option value="1">Description</option>\
												<option value="2">Submitted at</option>\
												<option value="3">Duration</option>\
											</select>\
											</div>\
											<div class="col-md-6">\
											<input class="form-control" type="text" id="search-by-column" placeholder="Search...">\
											</div>\
										</div>');
		
		searchByColumn(tableOuter);
		
} ); */

$(document).ready(function() {
		function searchByColumn(table){
			var defaultSearch = 0 // Galaxy ID
			
			$(document).on('change', '#search-column', function() {
				defaultSearch = this.value;
			});
			
			$(document).on('keyup', '#search-by-column', function(){
				table.search('').columns().search('').draw();
				table.column(defaultSearch).search(this.value).draw();
			});
		}
		
    var table = $('#historyTableInner').DataTable( {
			"searching": true,
			"fixedHeader": true,
			"responsive": true,
/* 			"order": [[0, 'asc'], [16, 'asc']],
			"orderFixed":{
				"post":[[0, 'asc'], [16, 'asc']]
			}, */
			"order": [[0, 'asc']],
			"rowsGroup": {"startRender":null, "endRender":null, "dataSrc":0},
			"dom": '<"filterSearch">rtip'
		});
		$("div.filterSearch").html('<div class="row">\
											<div class="col-md-5">\
											<select class="form-control" id="search-column">\
												<option value="0">Galaxy ID</option>\
												<option value="1">Optical u</option>\
												<option value="2">Optical v</option>\
												<option value="3">Optical g</option>\
												<option value="4">Optical r</option>\
												<option value="5">Optical i</option>\
												<option value="6">Optical z</option>\
												<option value="7">Infrared 3.6</option>\
												<option value="8">Infrared 4.5</option>\
												<option value="9">Infrared 5.8</option>\
												<option value="10">Infrared 8.0</option>\
												<option value="11">Infrared J</option>\
												<option value="12">Infrared H</option>\
												<option value="13">Infrared K</option>\
												<option value="14">Radio 1.4</option>\
												<option value="15">Method</option>\
												<option value="16">Redshift result</option>\
											</select>\
											</div>\
											<div class="col-md-6">\
											<input class="form-control" type="text" id="search-by-column" placeholder="Search...">\
											</div>\
										</div>');
		
		searchByColumn(table);
		
} );

