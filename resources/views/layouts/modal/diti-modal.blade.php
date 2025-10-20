<!-- sample modal content -->

<div class="modal fade" id="ditimodal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" id="diti-modal-content">
            {{-- <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="fmCreate">
                    @csrf
                    <input type="hidden" id="submiturl" value="employees">
            
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label required">First Name</label>
                            <input type="text" name="first_name" class="form-control form-control-solid" placeholder="First name">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required">Last Name</label>
                            <input type="text" name="last_name" class="form-control form-control-solid" placeholder="Last name">
                        </div>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">Type</label>
                            <select name="employee_type" class="form-select form-select-solid">
                                @foreach (config('constants.employee_types') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Cost</label>
                            <input type="number" name="employee_cost" class="form-control form-control-solid" placeholder="Cost">
                        </div>
                    </div>
            
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="job_title" class="form-control form-control-solid" placeholder="Job title">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Duty Hours</label>
                            <input type="number" name="duty_hours" class="form-control form-control-solid" placeholder="Hours">
                        </div>
                    </div>
            
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btnSave">Save</button>
                    </div>
                </form>
            </div> --}}

        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
