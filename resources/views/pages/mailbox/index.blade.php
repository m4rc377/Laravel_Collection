@extends('layout.app')

@section('title', ' | Mailbox')

@section('content')

    <section class="content-header">
        <h1>
            Mailbox

            @if($unreadMessages)
                <small>{{$unreadMessages}} new messages</small>
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Mailbox</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">

            @include('includes.flash_message')

            <div class="col-md-3">
                <a href="{{ url('admin/mailbox-create') }}" class="btn btn-primary btn-block margin-bottom">Compose</a>

                @include('pages.mailbox.includes.folders_panel')
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ Request::segment(3)==""?"Inbox":Request::segment(3) }}</h3>

                        <div class="box-tools pull-right">
                            <div class="has-feedback">
                                <form method="GET" action="{{ url('/admin/mailbox/' . Request::segment(3)) }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0" role="search">
                                    <input type="text" class="form-control input-sm" name="search" placeholder="Search Mail">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </form>
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>

                    @if(!$messages->isEmpty())
                        <!-- /.box-header -->
                        <div class="box-body no-padding">

                            @include('pages.mailbox.includes.mailbox_controls')

                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover table-striped">
                                    <tbody>

                                    @foreach($messages as $message)
                                        <tr>
                                            <td><input type="checkbox" value="1" data-id="{{ $message->id }}" class="check-message"></td>
                                            <td class="mailbox-star"><a href="#"><i class="fa {{ $message->is_important==1?'fa-star':'fa-star-o' }} text-yellow"></i></a></td>
                                            <td class="mailbox-name"><a href="{{ url('admin/mailbox-show') }}">{{ $message->sender->name }}</a></td>
                                            <td class="mailbox-subject">

                                                @if(Request::segment(3) == "Sent")
                                                    {{ $message->subject }}
                                                @else
                                                    @if($message->is_unread == 1)
                                                        <b>{{ $message->subject }}</b>
                                                    @else
                                                        {{ $message->subject }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="mailbox-attachment">
                                                @if($message->attachments->count() > 0)
                                                    <i class="fa fa-paperclip"></i>
                                                @endif
                                            </td>
                                            <td class="mailbox-date">@if($message->time_sent) {{ Carbon\Carbon::parse($message->time_sent)->diffForHumans()}}  @endif</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!-- /.table -->
                            </div>
                            <!-- /.mail-box-messages -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer no-padding">

                            @include('pages.mailbox.includes.mailbox_controls')

                        </div>
                    @else
                        <p>No messages found</p>
                    @endif
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('scripts')

    <script src="{{ asset('theme/views/mailbox/index.js') }}" type="text/javascript"></script>

@endsection