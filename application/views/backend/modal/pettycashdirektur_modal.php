
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="valid-state">Biaya <b>(Rp)</b></label>
                                    <input readonly type="number" name="biaya" class="form-control biaya" id="biaya" placeholder="Biaya" required>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea readonly class="form-control ket" placeholder="Masukkan Keterangan Pettycash" name="ket" id="ket" rows="4"></textarea>
                                    <label for="floatingTextarea">Keterangan Pettycash</label>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="divider">
                                    <div class="divider-text">Release Pettycash</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pembahasan">Release</b></label>
                                 <select class="form-select pembahasan" name="pembahasan" id="pembahasan" style="width:100%" required>
                                    <option value="">[Pilih Release]</option>
                                    <option value="RILIS">DISETUJUI</option>
                                    <option value="NONRILIS">DITOLAK</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control catatandirektur" placeholder="Masukkan Catatan Manajer" name="catatandirektur" id="catatandirektur" rows="4"></textarea>
                                    <label for="floatingTextarea">Catatan</label>
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

