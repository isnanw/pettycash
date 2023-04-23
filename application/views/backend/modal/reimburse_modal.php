
<!-- MODAL -->

<!-- Add Update Modal -->
<div class="modal fade text-left" id="modal_form_reimburse" tabindex="-1" role="dialog"
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
                            $attributes = array('class' => 'form-horizontal', 'id' => 'formreimburse');
                            echo form_open($this->uri->uri_string(), $attributes);
                        ?>
                        <div class="row">
                            <input type="hidden" class="id" name="id"/>
                            <b class="text-center">(*jpg,png,jpeg,webp dan ukuran maks 5mb)</b>
                            <div class="col-12 col-md-12 text-center">
                                <div  class="show_img"></div>
                                <br/>
                                <div class="form-group">
                                    <label for="formFileSm" class="form-label" id="text_1" name="text_1">Unggah Foto Bukti reimburse</label>
                                    <br/>

                                    <input type="file" class="form-control form-control-sm" id="picture_1" name="picture_1" accept=".jpg,.jpeg,.png,.webp" />

                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control ket" placeholder="Masukkan Keterangan reimburse" name="ket" id="ket"></textarea>
                                    <label for="floatingTextarea">Masukkan Keterangan reimburse</label>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="valid-state">Biaya <b>(Rp)</b></label>
                                    <input type="number" name="biaya" class="form-control biaya" id="biaya" placeholder="Biaya" required>
                                    <span class="help-block text-danger"></span>
                                </div>
                            </div>
                            <div name="pembahasan_1" id="pembahasan_1">
                            <hr>
                                <div class="col-12">
                                    <div class="divider">
                                        <div class="divider-text">Pembahasan Pengajuan Reimburse</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pembahasan">Bahas</b></label>
                                    <select class="form-select pembahasan" name="pembahasan" id="pembahasan" style="width:100%" required>
                                        <option value="">[Pilih Status]</option>
                                        <option value="DISETUJUI">DISETUJUI</option>
                                        <option value="DITOLAK">DITOLAK</option>
                                    </select>
                                    <span class="help-block text-danger"></span>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control catatanmanajer" placeholder="Masukkan Catatan Manajer" name="catatanmanajer" id="catatanmanajer" rows="4"></textarea>
                                        <label for="floatingTextarea">Catatan</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <div name="rilis_1" id="rilis_1">
                            <hr>
                                <div class="col-12">
                                    <div class="divider">
                                        <div class="divider-text">Rilis Pengajuan Reimburse</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rilis">Bahas</b></label>
                                    <select class="form-select rilis" name="rilis" id="rilis" style="width:100%" required>
                                        <option value="">[Pilih Status]</option>
                                        <option value="RILIS">RILIS</option>
                                        <option value="NONRILIS">NONRILIS</option>
                                    </select>
                                    <span class="help-block text-danger"></span>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control catatandirektur" placeholder="Masukkan Catatan Direktur" name="catatandirektur" id="catatandirektur" rows="5"></textarea>
                                        <label for="floatingTextarea">Catatan</label>
                                        <span class="help-block text-danger"></span>
                                    </div>
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
                        <button type="submit" class="btn btn-success ml-1 btnSave" id="btnSave" onclick="addreimburse()">
                            <i class="bx bx-check d-sm-none"></i>Save</button>
                    </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- Add Update Modal -->
<!-- END MODAL -->

