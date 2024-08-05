@foreach ($stocks as $item)
    <div class="modal fade" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image of
                    @if ($item->itemBy)
                        {{ $item->itemBy->name }}
                    @endif    
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                         @if ($item->itemBy)
                        <img src="{{ url('/public/images/material_promotion/' . $item->itemBy->img_ref) }}"
                            id="" class="img-fluid shadow-lg" />
                        @endif    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
