<div class="modal" id="new-advertiser-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <div class="modal-body">
            {!! Form::model($Advertiser, ['route' => 'advertisers.store', 'class' => 'form-horizontal']) !!}
                <fieldset class="col-lg-12">
                    @include('advertisers.form')
                </fieldset>
            {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save-button">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
