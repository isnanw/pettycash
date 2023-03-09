
<!-- MODAL -->

<!-- Add Update Modal -->
<div class="modal fade text-left" id="modal_form_pettycash" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel120" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">

                        <h3 class="modal-title white" id="myModalLabel120"></h3>
                        <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
            <div class="modal-body form">

                        <?php
                            $attributes = array('class' => 'form-horizontal', 'id' => 'formpettycash');
                            echo form_open($this->uri->uri_string(), $attributes);
                        ?>
                        <div class="row">
                            <input type="hidden" class="id" name="id"/>
                            <div class="form-group">
                                    <label for="bagian">Bagian</label>
                                    <select class="form-select bagian" name="bagian" id="bagian" style="width:100%">
                                            <option value="">[Pilih Bagian]</option>
                                            <?php foreach ($bagian->result() as $row) : ?>
                                                <option value="<?php echo $row->id_bagian;?>"><?php echo $row->namabagian;?></option>
                                            <?php endforeach;?>
                                    </select>
                                    <span class="help-block text-danger"></span>
                                </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="biaya">Biaya <b>(Rp)</b></label>
                                    <input type="number" name="biaya" class="form-control biaya" id="biaya" placeholder="Biaya" required>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control ket" placeholder="Masukkan Keterangan Pettycash" name="ket" id="ket"></textarea>
                                    <label for="floatingTextarea">Masukkan Keterangan pettycash</label>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <?php
                            echo form_close();
                        ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                            data-bs-dismiss="modal">
                            <i class="bx bx-x d-sm-none"></i>Cancel</button>
                        <button type="submit" class="btn btn-success ml-1 btnSave" id="btnSave" onclick="addpettycash()">
                            <i class="bx bx-check d-sm-none"></i>Save</button>
                    </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- Add Update Modal -->
<!-- END MODAL -->

