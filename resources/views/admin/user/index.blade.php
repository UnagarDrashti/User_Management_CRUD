@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-5">
            <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Create User</a>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">User List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center data-table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Profile Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        {{-- model --}}
                        <div class="modal fade" id="userShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Show User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Name:</strong> <span id="user-name"></span></p>
                                    <p><strong>Email:</strong> <span id="user-email"></span></p>
                                    <p><strong>Phone:</strong> <span id="user-phone"></span></p>
                                    <p><strong>Gender:</strong> <span id="user-gender"></span></p>
                                    <p><strong>Profile Image:</strong> <img src="" id="user-profile_img" alt="image" border="0" width="100" class="img-rounded" align="center"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    $(function () {
      
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('user.index') }}",
          columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
              {data: 'profile_img', name: 'profile_img'},
              {data: 'name', name: 'name'},
              {data: 'email', name: 'email'},
              {data: 'phone', name: 'phone'},
              {data: 'gender', name: 'gender'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
      
    });

    $(document).ready(function () {
       /* When click show user */
        $('body').on('click', '#show-user-info', function () {
          var userURL = $(this).data('url');
          $.get(userURL, function (data) {
                $('#userShowModal').modal('show');
                $('#user-name').text(data.name);
                $('#user-email').text(data.email);
                $('#user-phone').text(data.phone);
                $('#user-gender').text(data.gender);
                if (data.profile_img_url) {
                    $('#user-profile_img').attr('src', data.profile_img_url);
                }
          })
       }); 

        $('body').on('click', '.btn-danger', function (e) { 
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(this).data('remote');
            // confirm then
            if (confirm('Are you sure you want to delete this?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {method: 'DELETE'}
                }).always(function (data) {
                    $('.data-table').DataTable().draw(false);
                });
            }
        });
    });
</script>
@endsection