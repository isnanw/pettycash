<div class="page-content">
  <!------- breadcrumb --------->
  <?php $this->load->view("backend/_partials/breadcrumb.php") ?>
  <!------- breadcrumb --------->

  <!-- Post Datatables -->
  <section id="input-validation">
      <div class="row">
          <div class="col-12 col-xl-12">
              <div class="card">
                  <div class="card-body">
                      <div class="btn-group mb-3  float-end" role="group" aria-label="Basic example">
                          <a class="btn icon btn-sm btn-success" id="btn-validate-import" onclick="add_belanja()"><i class="bi bi-plus"></i></a>
                      </div>
                      <br/><br/>
                      <div class="table-responsive">
                          <table id="mytable" class="table table-bordered mb-0 text-sm">
                              <thead>
                                  <tr>
                                      <th class="col-1">No</th>
                                      <th class="col-5">Belanja Item</th>
                                      <th class="col-5">Harga</th>
                                      <th class="col-1">Aksi</th>
                                  </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:right">Total:</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!-- Post Datatables END -->

    <!------- FOOTER --------->
        <?php $this->load->view("backend/_partials/footer.php") ?>
    <!------- FOOTER --------->

<!-- </div>
</div> -->

<!------- TOASTIFY JS --------->
    <?php $this->load->view("backend/_partials/toastify.php") ?>

<!------- TOASTIFY JS --------->


<script type="application/javascript">
    var save_method;
    var table;
    var csfrData = {};

    csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo
    $this->security->get_csrf_hash(); ?>';
    $.ajaxSetup({
        data: csfrData
    });

    $(document).ready(function() {
        //datatables
        table = $('#mytable').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            //"searching": false,
            "order": [], //Initial no order.
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('backend/belanja/get_ajax_list')?>",
                "type": "POST",
                "data": function (data) {
                },
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ 0,1,2 ], //first column
                    "orderable": false, //set not orderable
                },
            ],
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;


            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?

                    i.replace(/[\Rp, ,.]/g, '')*1 :

                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var test = (pageTotal).toLocaleString(undefined,
                    { minimumFractionDigits: 0 }
                );
            // Update footer
            $('.totalbiaya').val(pageTotal);
            $( api.column( 2 ).footer() ).html(

                'Rp. '+test +''

            );
        }
        });

        $("#namabelanja").change(function(){
            $(this).parent().parent().removeClass('help-block text-danger');
            $(this).next().empty();
        });

    });

    function add_belanja()
    {
        save_method = 'add';
        $('#formbelanja')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.show_record').empty();
        $('.help-block').empty();
        $('#modal_form_belanja').modal('show');
        $('.modal-title').text('Tambah belanja');
    }

    function edit_belanja(id_belanja)
    {
        save_method = 'update';
        $('#formbelanja')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form_belanja').modal('show');
        $('.modal-title').text('Edit belanja');

        $.ajax({
            url : "<?php echo site_url('backend/belanja/ajax_edit/')?>/" + id_belanja,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id_belanja);
                $('[name="namabelanja"]').val(data.namabelanja);

                $('#modal_form_belanja').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null,false);
    }

    function addbelanja()
    {
        $('#btnSave').text('saving...');
        $('#btnSave').attr('disabled',true);
        var url;

        if(save_method == 'add') {
            url = "<?php echo site_url('backend/belanja/add')?>";
        } else {
            url = "<?php echo site_url('backend/belanja/edit')?>";
        }


        $.ajax({
            url : url,
            type: "POST",
            data: $('#formbelanja').serialize(),
            dataType: "JSON",
            success: function(data)
            {

                if(data.status)
                {
                    toastify_success();
                    $('#modal_form_belanja').modal('hide');
                    reload_table();
                }
                else
                {
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                    }
                }
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Save');
                $('#btnSave').attr('disabled',false);

            }
        });

    }


    $(document).on("click", "#deletebelanja", function(e) {
        e.preventDefault();

        var idkon = $(this).attr("value");

        Swal.fire({
            title: "Apakah kamu yakin ingin menghapus Data ini?",
            text: "Data ini akan di hapus secara Permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url : "<?php echo site_url('backend/belanja/deletebelanja')?>",
                    data: {
                        idkon: idkon,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.res == "success") {
                            Swal.fire(
                                "Deleted!",
                                "Data berhasil dihapus.",
                                "success"
                            );

                            reload_table();
                        }
                    },
                });
            }
        });
    });

        $(document).on("click", "#lock", function(e) {
            e.preventDefault();

            var id_belanja = $(this).attr("value");

            Swal.fire({
                title: "Aktif/ Nonaktif Konsumen ini ?",
                text: "Konsumen yang di Nonaktifkan menandakan sudah tidak melakukan Transaksi selama 1 bulan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Proses!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url : "<?php echo site_url('backend/konsumen/lock')?>",
                        data: {
                            id_belanja: id_belanja,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.res == "success") {
                                Swal.fire(
                                    "Success!",
                                    "Proses berhasil dilakukan.",
                                    "success"
                                );

                                reload_table();
                            }
                        },
                    });
                }
            });
        });
</script>

</body>
</html>
