
<div class="page-content">
    <!------- breadcrumb --------->
        <?php $this->load->view("backend/_partials/breadcrumb.php") ?>
    <!------- breadcrumb --------->
    <section id="input-validation">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card">
            <div class="card-body">
                <!-- FILTER -->

                        <?php if($this->session->userdata('access') == '1'):?>
                        <p class="text-subtitle text-muted">Filter Data</p>
                        <form id="form-filter">
                            <div class="row">
                            <div class="col-12 col-md-4">
                                <select class="form-select id_user_pettycash" name="id_user_pettycash" id="id_user_pettycash" style="width:100%" required>
                                    <option value="">[Pilih Karyawan]</option>
                                    <?php foreach ($karyawan->result() as $row) : ?>
                                        <option value="<?php echo $row->user_id;?>"><?php echo $row->user_name;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                                    <div class="col-12 col-md-4">
                                <div class="btn-group mb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="btn-filter" class="btn btn-primary"><i class="bi bi-search"></i> Filter Data</button>&nbsp;&nbsp;
                                    <button type="button" id="btn-reset" class="btn btn-success"><i class="bi bi-bootstrap-reboot"></i> Refresh</button>
                                </div>
                            </div>
                            </div>
                        </form>
                    <hr>
                    <a class="btn icon btn-sm btn-success float-end" onclick="add_pettycash()"><i class="bi bi-plus"></i></a>&nbsp;&nbsp;
                    <br/><br/>
                        <?php else:?>
                        <?php endif;?>

<!-- FILTER -->
                <div class="table-responsive">
                    <table id="mytable" class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <!-- <th class="col-2">Foto</th> -->
                            <th>Tanggal</th>
                            <th>Biaya</th>
                            <th>Keterangan</th>
                            <th>Karyawan / Atasan</th>
                            <th>Status</th>
                            <th class="col-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
                </div>
            </div>
        </div>
    </section>
</div>
        <!------- FOOTER --------->
            <?php $this->load->view("backend/_partials/footer.php") ?>
        <!------- FOOTER --------->
        </div>
    </div>



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

        "processing": true,
        "serverSide": true,
        "order": [],

        "ajax": {
            "url": "<?php echo site_url('backend/pettycashmanajer/get_ajax_list')?>",
            "type": "POST",
            "data": function ( data ) {
                data.id_user_pettycash = $('#id_user_pettycash').val();
            }
        },


        "columnDefs": [
        {
            "targets": [ 0,1,2,3,4,5],
            "orderable": false,
        },
        ],

    });


    $("#nama").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $('#btn-filter').click(function(){
        table.ajax.reload();
    });
    $('#btn-reset').click(function(){
        $('#form-filter')[0].reset();
        $("#id_user_pettycash").select2("destroy");
        $("#id_user_pettycash").select2({
            cache: false,
            theme: "bootstrap-5",
        });
        table.ajax.reload();
    });

            $("#id_user_pettycash").select2({
                cache: false,
                theme: "bootstrap-5",
            });

});



function add_pettycash()
{
    save_method = 'add';
    $('#formpettycash')[0].reset();
    $('.show_edit').empty(); // clear error class
    $('.show_img').empty(); // clear error class
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form_pettycash').modal('show');
    $('.modal-title').text('Tambah pettycash');
}

function edit_pettycashmanajer(id_pettycash)
{
    save_method = 'update';
    $('#formpettycash')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form_pettycash').modal('show');
    $('.modal-title').text('Pembahasan Pengajuan Pettycash');
    var base_url = '<?php echo base_url(); ?>';
    $.ajax({
        url : "<?php echo site_url('backend/pettycashmanajer/ajax_edit/')?>/" + id_pettycash,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id_pettycash);
            $('[name="ket"]').val(data.ket_pettycash);
            $('[name="biaya"]').val(data.biaya_pettycash);
            $('[name="catatanmanajer"]').val(data.catatan_manajer);
            $('#modal_form_pettycash').modal('hide');
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

function addpettycash()
{
    var id = $(".id").val();
    var status = $("#pembahasan").val();
    var catatan_manajer = $("#catatanmanajer").val();

    var fd = new FormData();
    fd.append("id", id);
    fd.append("pembahasan", status);
    fd.append("catatanmanajer", catatan_manajer);
    fd.append("<?php echo $this->security->get_csrf_token_name(); ?>", '<?php echo $this->security->get_csrf_hash(); ?>');
    $('#btnSave').text('saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('backend/pettycashmanajer/add')?>";
    } else {
        url = "<?php echo site_url('backend/pettycashmanajer/edit')?>";
    }

    $.ajax({
        url : url,
        type: "POST",
        url : url,
        data: fd,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(data)
        {

            if(data.status)
            {
                toastify_success();
                $('#modal_form_pettycash').modal('hide');
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

$(document).on("click", "#del", function(e) {
        e.preventDefault();
        var id = $(this).attr("value");
        Swal.fire({
            title: "Apakah kamu yakin ingin menghapus pettycash ini?",
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
                    url : "<?php echo site_url('backend/pettycashmanajer/delete')?>",
                    data: {
                        id: id,
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
</script>
</body>
</html>
