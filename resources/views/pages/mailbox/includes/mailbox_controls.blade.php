<div class="mailbox-controls">

    <!-- Check all button -->
    @if(Request::segment(3) != 'Trash')
        <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
        </button>
    @endif
    <div class="btn-group">

        @if(Request::segment(3)==''||Request::segment(3)=='Inbox')
            <button type="button" class="btn btn-default btn-sm mailbox-star-all" title="toggle important state"><i class="fa fa-star"></i></button>
            <button type="button" class="btn btn-default btn-sm mailbox-trash-all" title="add to trash"><i class="fa fa-trash-o"></i></button>
            <button type="button" class="btn btn-default btn-sm mailbox-reply" title="reply"><i class="fa fa-reply"></i></button>
            <button type="button" class="btn btn-default btn-sm mailbox-forward" title="forward"><i class="fa fa-mail-forward"></i></button>
        @elseif(Request::segment(3) == 'Sent')
            <button type="button" class="btn btn-default btn-sm mailbox-star-all" title="toggle important state"><i class="fa fa-star"></i></button>
            <button type="button" class="btn btn-default btn-sm mailbox-trash-all" title="add to trash"><i class="fa fa-trash-o"></i></button>
            <button type="button" class="btn btn-default btn-sm" title="forward"><i class="fa fa-mail-forward"></i></button>
        @elseif(Request::segment(3) == 'Drafts')
            <button type="button" class="btn btn-default btn-sm mailbox-star-all" title="toggle important state"><i class="fa fa-star"></i></button>
            <button type="button" class="btn btn-default btn-sm mailbox-trash-all" title="add to trash"><i class="fa fa-trash-o"></i></button>
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