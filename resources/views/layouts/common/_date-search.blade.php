<div class="main-card card mb-2">
    <div class="card-header">
        <div class="row">
            <div class="col-md-10">
                <h5>Search</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route($page.'.index') }}" method="get">
            {{-- @csrf --}}
            <div class="form-row">
                <div class="col-md-3 p-1">
                    <label for="fromdate" id="refLabel">{{ __('From Date') }}</label>
                    <input type="date" class="form-control" id="fromdate" name="fromdate"
                        value="{{ date('Y-m-d', strtotime($fromdate)) }}">
                </div>
                <div class="col-md-3 p-1">
                    <label for="todate" id="refLabel">{{ __('To Date') }}</label>
                    <input type="date" class="form-control" id="todate" name="todate"
                        value="{{ date('Y-m-d', strtotime($todate)) }}">
                </div>

             
                <div class="col-md-2 p-2 mt-auto">

                    <button class="btn btn-success" type="submit">Search</button>

                </div>

            </div>
        </form>
    </div>
</div>