<div class="modal modal-slide-in fade general_modal"
     data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">>
    <div class="modal-dialog sidebar-lg">
        <form class="modal-content pt-0" id="general-form" novalidate>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×
            </button>
            <div class="modal-header mb-1">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
            </div>
            <div class="modal-body flex-grow-1">
                @yield('form_input')
                <button type="submit" class="btn btn-primary data-submit me-1">{{trans('admin.save')}}</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{trans('admin.cancel')}}
                </button>
            </div>
        </form>
    </div>
</div>
