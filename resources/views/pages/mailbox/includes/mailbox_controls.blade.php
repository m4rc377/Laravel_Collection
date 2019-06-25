<div class="mailbox-controls">
    <!-- Check all button -->
    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
    </button>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm" title="toggle important state"><i class="fa fa-star"></i></button>
        <button type="button" class="btn btn-default btn-sm" title="add to trash"><i class="fa fa-trash-o"></i></button>

        @if(Request::segment(3) != 'Drafts')
            <button type="button" class="btn btn-default btn-sm" title="reply"><i class="fa fa-reply"></i></button>
            <button type="button" class="btn btn-default btn-sm" title="forward"><i class="fa fa-mail-forward"></i></button>
        @else
            <button type="button" class="btn btn-default btn-sm" title="send"><i class="fa fa-mail-forward"></i></button>
        @endif
    </div>
    <div class="pull-right">

        {{$messages->currentPage()}}-{{$messages->perPage()}}/{{$messages->total()}}

        <div class="btn-group">
            {!! $messages->appends(['search' => Request::get('search')])->render('vendor.pagination.mailbox') !!}
        </div>

        <!-- /.btn-group -->
    </div>
    <!-- /.pull-right -->
</div>