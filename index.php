<!-- <?php
        // Include config file
        require_once "mapAttribute.php";
        ?> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link media="all" type="text/css" rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <link media="all" type="text/css" rel="stylesheet" href="assets/plugins/datatables/dataTables.bootstrap.min.css">

    <script src="assets/plugins/datatables/jquery.dataTables.js"></script>

    <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

    <script src="assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

    <script src="assets/plugins/datatables/dataTables.scrollingPagination.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css"> -->
    <!-- <script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script> -->
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }

        table tr td:last-child {
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row" style="display: flex;justify-content: center;">
            <div class="col-10 ">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align: center;">
                        <h2>Product Details</h2>
                    </div>
                    <div class="panel-body">

                        <table class="table table-stripe table-bordered" id="attributes">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Product ID</th>
                                    <th>Product</th>
                                    <th>Sears Category</th>
                                    <th>Attributes</th>
                                    <th>Variation Attribute</th>
                                    <th>Map Attribut</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" data-backdrop="static" id="modal_map_attribute">
            <div class="modal-dialog">
                <div class="modal-content box">
                    <form id="frm_map_attribute">
                        <input id="modal_product_id" name="product" type="hidden" />
                        <div class="modal-header">
                            <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                <span aria-hidden="true">
                                    X
                                </span>
                            </button>
                            <h4 class="modal-title">
                                Map Attributes
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-dismissible alert-success" id="attr_success" style="display:none;">
                                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                    <span aria-hidden="true">
                                        ×
                                    </span>
                                </button>
                                Attributes mapped successfully
                            </div>
                            <div class="alert alert-dismissible alert-danger" id="attr_danger" style="display:none;">
                                <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                                    <span aria-hidden="true">
                                        ×
                                    </span>
                                </button>
                                There were some error try again
                            </div>
                            <div>
                                <h4 class="text-center">
                                    <span id="modal_product_name"></span>
                                </h4>
                                <h5 class="text-center">
                                    <p id="modal_category_name"></p>
                                </h5>
                            </div>
                            <div class="attribute">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success save-data btn_map" type="submit">
                                Map Attributes
                            </button>
                            <button class="btn btn-danger btn_cat_reset" id="btn_cat_reset" type="button">
                                Reset
                            </button>
                            <button aria-label="Close" class="btn btn-default" data-dismiss="modal" type="button">
                                Close
                            </button>
                        </div>
                    </form>
                    <div class="overlay" id="modal_spin" style="display:none;">
                        <i class="fa fa-spin fa-spinner">
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
function modalMapattribute(params) {
    $(".attribute div").remove();
    $('[id$=_error]').text('');
    $('span#modal_product_name').text(params.products);
    $('p#modal_category_name').text(params.sears_category);
    $('#modal_product_id').val(params.id);
    var product_attr = params.attributes;
    var product_id = params.id;
    var class_id = params.class_id;
    console.log(params,product_attr,product_id,class_id)
    $.ajax({
        url: "modal-map-attr.php",
        type: "GET",
        dataType: "json",
        data: {
            class_id: class_id,
            product_id: product_id,
           
        },
        success: function(dataResult) {
            console.log(dataResult);
            $(".attribute").append(dataResult);
        }
    });
    $('#modal_map_attribute').modal('show');
}
$("#frm_map_attribute").on('submit', function(e) {
        e.preventDefault();
        // Serialize the data in the form
        var serializeData = $("#frm_map_attribute").serialize();
        // Fire off the request to /Attribute Maping
        $.ajax({
            url: "store-attribute.php",
            type: "post",
            data: serializeData,
            dataType: 'Json',
            beforeSend: function() {
                $('.save-data').addClass('disabled').text('Loading...');
                $('.alert-dismissible').hide();
                $('#atrr_errors').hide();
                $('#product_count').hide();
                $('[id$=_error]').text('');
                // myShow();
            },
            complete: function() {
                $('.save-data').removeClass('disabled').text('Map Attribute');
            },
            success: function(respObj) {
                if (respObj.success) {
                    $('#aler_success').show().delay(3000).fadeOut('slow');
                    $('#attr_success').show().delay(5000).fadeOut('slow');
                    $('.save-data').removeClass('disabled').text('Map Attribute');
                    $('#modal_map_attribute').delay(6000).modal('hide');
                    $('#arrange_spin').hide();
                    var redrawtable = $('#attributes').dataTable();
                    redrawtable.fnStandingRedraw();
                }
                if (respObj.errors) {
                    $('#attr_danger').show().delay(3000).fadeOut('slow');
                    $('#attr_errors').show().delay(5000).fadeOut('slow');
                    $.each(respObj.errors, function(k, v) {
                        $('#' + k + '_error').text(v);
                    });
                }
            }
        });
    });
    $(document).ready(function() {
        var attributes =  $('#attributes').dataTable({
            "bProcessing": false,
            // "bServerSide": true,
            "autoWidth": true,
            "bStateSave": true,
            "bSearchDelay": 7000,
            "sAjaxSource": "mapAttribute.php",
            "sPaginationType": "listbox",
            "oLan guage": {
                "sLengthMenu": "Display _MENU_ records"
            },
            "aaSorting": [
                [2, "asc"]
            ],
            "aoColumns": [{
                    "mData": "id",
                    "visible": false
                },
                {
                    "mData": "id",
                    "visible": true,
                },
                {
                    "mData": "products",
                    "visible": true,
                },
                {
                    "mData": "sears_category",
                    "visible": true,
                },
                {
                    'mData': 'attributes',
                    "visible": true,
                    sWidth: "15%",
                    mRender: function(v, t, o) {
                        html = "<ul style=\"list-style: none\">";
                        if (v != null) {
                            Object.keys(v).forEach(key => {
                                html += "<li>" + key + " : " + v[key] + "<li>";
                            });
                        }
                        html += "</ul>";
                        return html;
                    }
                },
                {
                    'mData': 'variation_attributes',
                    "visible": true,
                    sWidth: "15%",
                    mRender: function(v, t, o) {
                        html = "<ul style=\"list-style: none\">";
                        if (v != null) {
                            Object.keys(v).forEach(key => {
                                html += "<li>" + key + " : " + v[key] + "<li>";
                            });
                        }
                        html += "</ul>";
                        return html;
                    }
                },
                {
                    mData: null,
                    sWidth: "15%",
                    bSortable: false,
                    sClass: 'text-center',
                    mRender: function(v, t, o) {
                        var params = JSON.stringify(o).replaceAll('\'', '');

                        act_html = "<div class='btn-group'>" +
                            "<button onclick='modalMapattribute(" + params +
                            ")'  data-toggle='tooltip' title='Map Attribute' data-placement='top' class='btn btn-xs btn-primary'><i class='fa fa-exchange'></i></button>" +
                            "</div>";
                        return act_html;
                    }
                }
            ],

        });
    });
</script>

</html>