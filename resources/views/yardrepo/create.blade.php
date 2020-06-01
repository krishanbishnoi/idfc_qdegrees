@extends('layouts.master')

@section('title', '| Users')

@section('sh-detail')
    Create New
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <strong>Yard Repo</strong>
        </div>
        <div class="card-body card-block">
            {!! Form::open(
                     array(
                       'route' => 'yardrepo.store',
                       'class' => 'form-horizontal',
                       'role'=>'form',
                       'data-toggle'=>"validator")
                     ) !!}

        <div class="row">
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Yard Repo Name</label>
                    <input type="text" id="text-input" name="name" placeholder="Yard Repo Name"
                                                        class="form-control" value="{{old('name')}}">
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Branch Name</label>
                    <select class="form-control" name="branch_name" id="branch_name"  value="{{old('branch_name')}}">
                        <option value="">Choose Branch</option>
                        @foreach ($branch as $item)
                            <option value="{{$item->id}}" {{($item->id == old('branch_name'))?'selected':''}} >{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Product</label>
                    <select class="form-control" id="product" name="product_name" value="{{old('product_name')}}">

                    </select>
                </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                    <label for="text-input" class=" form-control-label">Location</label>
                    <input type="text" id="text-input" name="location" placeholder="location"
                                                        class="form-control" value="{{old('location')}}">
                </div>
            </div>
        </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Submit
                </button>
                <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                </button>
            </div>
            </form>
        </div>

    </div>


@endsection
@section('js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        jQuery(document).on('ready',function(e){
            if("{{old('branch_name')}}"){
            getProduct("{{old('branch_name')}}");
            }
        })
        jQuery('#branch_name').change(function () {
            var id = jQuery(this).val();
            if (id) {
                getProduct(id)
            }
        });
     function getProduct(id){
        jQuery.ajax({
                    type: "get",
                    url: " {{url('get_product')}}/"+id+'/branch',
                    success: function (res) {
                        var data='<option>Choose Product</option>';
                        if (res) {
                            var obj=res
                            obj.data.forEach(function(item, index){
                                data=data+'<option value="'+item.id+'">'+item.name+'</option>'
                            });  
                        }
                        jQuery('#product').html(data)
                        jQuery('#product').val("{{old('product_name')}}")
                    }
                });
     } 
    </script>
@endsection