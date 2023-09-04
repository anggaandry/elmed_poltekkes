@extends('admin/master')

@section('title', 'Edit Role')

@section('breadcrumb')
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Akun</a></li>
            <li class="breadcrumb-item"><a href="{{ url('4dm1n/role') }}">Role</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Edit Role</a></li>
        </ol>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{ url('/4dm1n/role/edit') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $role_data->id }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Nama role</label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="Ex. Admin/Inventory/Keuangan/Admin Fakutas A"
                                    value="{{ $role_data->name }}" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Akses Prodi</label>
                                <select class="form-select form-select-lg" name="prodi_id"
                                    onchange="change_prodi(this.value)">
                                    <option value="" selected>Semua akses</option>
                                    @foreach ($spc_data as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($role_data->prodi_id == $item->id) selected @endif>{{ $item->program->name }}
                                            {{ $item->study_program->name }} - {{ $item->category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label mb-3">Akses Menu

                                </label>
                                <div class="float-end">
                                    <button class="btn btn-success btn-xs" type="button" onclick="check_all()"><i
                                            class="fa fa-check"></i>
                                        check
                                        all</button>
                                    <button class="btn btn-danger btn-xs" type="button" onclick="uncheck_all()"><i
                                            class="fa fa-times"></i> uncheck
                                        all</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Menu</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Add</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                                $category = '';
                                            @endphp
                                            @foreach ($menu_data as $item)
                                                @if ($item->category != '')
                                                    @if ($item->category != $category)
                                                        <tr style="background-color: #eee;">
                                                            <td class="text-center">{{ $i++ }}</td>
                                                            <td colspan="6" class="font-w900">{{ $item->category }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                <tr style="{{ $item->category == '' ? 'background-color: #eee;' : '' }}">
                                                    <td class="text-center">{{ $item->category == '' ? $i++ : '' }}</td>
                                                    <td class="{{ $item->category == '' ? 'font-w900' : '' }}">
                                                        {{ $item->name }}</td>

                                                    <td class="text-center">
                                                        @if ($item->has_view)
                                                            <div class="custom-checkbox checkbox-primary">
                                                                <input type="checkbox"
                                                                    class="form-check-input {{ $item->only_prodi == 0 ? 'only-prodi' : '' }}"
                                                                    @if ($item->access) @if ($item->access->view_access == 1)
                                                                            checked @endif
                                                                    @endif
                                                                name="cbView{{ $item->id }}"
                                                                onchange="select_c({{ $item->id }});">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->has_add)
                                                            <div class="custom-checkbox checkbox-success">
                                                                <input type="checkbox"
                                                                    class="form-check-input {{ $item->only_prodi == 0 ? 'only-prodi' : '' }}"
                                                                    @if ($item->access) @if ($item->access->add_access == 1)
                                                                    checked @endif
                                                                    @endif
                                                                name="cbAdd{{ $item->id }}">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->has_edit)
                                                            <div class="custom-checkbox checkbox-info">
                                                                <input type="checkbox"
                                                                    class="form-check-input {{ $item->only_prodi == 0 ? 'only-prodi' : '' }}"
                                                                    @if ($item->access) @if ($item->access->edit_access == 1)
                                                                    checked @endif
                                                                    @endif
                                                                name="cbEdit{{ $item->id }}">
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->has_delete)
                                                            <div class="custom-checkbox checkbox-danger">
                                                                <input type="checkbox"
                                                                    class="form-check-input {{ $item->only_prodi == 0 ? 'only-prodi' : '' }}"
                                                                    @if ($item->access) @if ($item->access->delete_access == 1)
                                                                    checked @endif
                                                                    @endif
                                                                name="cbDelete{{ $item->id }}">
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    if ($item->category != '') {
                                                        $category = $item->category;
                                                    }
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="width:100%;">
                            <a class="btn  btn-secondary" href="{{ url('4dm1n/role') }}"><span
                                    class="btn-icon-start text-secondary"><i
                                        class="fa fa-arrow-left-long color-secondary"></i>
                                </span>Kembali</a>
                            <button class="btn  btn-info float-end mb-3" type="submit"><span
                                    class="btn-icon-start text-info"><i class="fa fa-save color-info"></i>
                                </span>Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var prodi = $('select[name=prodi_id]').val();
            if (prodi != "") {
                $('input.only-prodi:checkbox').prop('checked', false);
                $('input.only-prodi:checkbox').prop('disabled', true);
            } else {
                $('input:checkbox').prop('disabled', false);
            }
        });

        function select_c(id) {
            var checked = $('input:checkbox[name=cbView' + id + ']:checked').val();
            if (!checked) {
                $('input:checkbox[name=cbAdd' + id + ']:checked').prop('checked', false);
                $('input:checkbox[name=cbEdit' + id + ']:checked').prop('checked', false);
                $('input:checkbox[name=cbDelete' + id + ']:checked').prop('checked', false);
            }
        }

        function check_all() {
            $('input:checkbox').prop('checked', true);
            var prodi = $('select[name=prodi_id]').val();
            if (prodi != "") {
                $('input.only-prodi:checkbox').prop('checked', false);
            }
        }

        function uncheck_all() {
            $('input:checkbox').prop('checked', false);
        }

        function change_prodi(val) {
            if (val != "") {
                $('input.only-prodi:checkbox').prop('checked', false);
                $('input.only-prodi:checkbox').prop('disabled', true);
            } else {
                $('input.only-prodi:checkbox').prop('checked', true);
                $('input:checkbox').prop('disabled', false);
            }
        }
    </script>
@endsection
