<div>
    <section class="section">
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <div class="mr-auto">
                        <div class="form-inline">
                            <div class="input-group">
                                <select class="custom-select" wire:model='perPage'>
                                    @foreach ($perPageOptions as $option)
                                        <option>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-group">
                                <input type="search" class="form-control ml-2" name="search"
                                    wire:model.debounce.750ms='search'>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>

                            @if ($loadingEnabled)
                                <div wire:loading>
                                    <div class="ml-2 spinner-border spinner-border-sm"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-header-action">
                        @if ($rightTableComponent)
                            @include($rightTableComponent)
                        @endif
                    </div>
                </div>

                <div class="p-0 card-body">
                    <table class="table mb-0 table-bordered">
                        <thead>
                            @include('datatable::includes.columns')
                        </thead>
                        <tbody>
                            @includeWhen($models->isEmpty(), 'datatable::includes.empty')
                            @includeWhen($models->isNotEmpty(), 'datatable::includes.data')
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-whitesmoke d-flex align-items-center">
                    {{ $models->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
