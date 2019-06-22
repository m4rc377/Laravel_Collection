<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Folders</h3>

        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach($folders as $folder)
                <li class="{{ Request::segment(3)==''&&$folder['name']=='Inbox'?'active':(Request::segment(3)==$folder['name']?'active':'') }}"><a href="{{ url('admin/mailbox/' . $folder['name']) }}"><i class="{{ $folder['icon'] }}"></i> {{ $folder['name'] }}
                    @if((Request::segment(3)==""||Request::segment(3)=="Inbox") && $folder['name']=='Inbox' && $unreadMessages)<span class="label label-primary pull-right">{{$unreadMessages}}</span> @endif </a></li>
            @endforeach
        </ul>
    </div>
    <!-- /.box-body -->
</div>