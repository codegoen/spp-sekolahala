<div>
    <div class="section">
        <div class=" section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <form>
                            <div class="card-body mt-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group custom-select-icon" wire:ignore>
                                            <select class="custom-select" id="search-student" wire:model='student'>
                                                <option></option>
                                                @foreach ($students as $item)
                                                    <option value="{{ $item->id }}">
                                                        @if ($item->nis)
                                                            {{ $item->name }} - {{ $item->nis }}
                                                        @else
                                                            {{ $item->name }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-lg-4">
                                        <div class="form-group custom-select-icon" wire:ignore>
                                            <select class="custom-select" id="search-bill" wire:model='bill'>
                                                <option></option>
                                                @foreach ($bills as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col col-lg-2">
                                        <div class="form-group custom-select-icon" wire:ignore>
                                            <select class="custom-select" id="search-year" wire:model='year'>
                                                <option></option>
                                                @foreach ($years as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <button wire:click.prevent='search' type="button" class="btn btn-primary"
                                            style="padding: 8px 30px;"
                                            {{ is_null($student) || is_null($bill) || is_null($year) ? 'disabled' : '' }}>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($billResult && $billResult->monthly)
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Detail Pembayaran</h4>
                            </div>
                            <div class="card-body">
                                <h6>Semester Genap</h6>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Tagihan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (\Modules\Utils\Semester::even() as $i => $e)
                                            @php
                                                $any = isset($payments[$i]);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $e }}</strong>
                                                </td>
                                                <td>{{ idr($billResult->nominal) }}</td>
                                                <td>{{ $i }}</td>
                                                <td style="width: 9rem;">
                                                    <button
                                                        class="btn btn-info btn-sm {{ !$any && empty($payments[$i]) ? 'not-allowed' : '' }}"
                                                        data-toggle="collapse" role="button"
                                                        data-target="#table-detail-{{ $i }}"
                                                        {{ !$any && empty($payments[$i]) ? 'disabled' : '' }}>
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button wire:click.prevent='pay("{{ $i }}")'
                                                        class="btn btn-sm btn-success">
                                                        <i class="far fa-money-bill-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @if ($any)
                                                <tr>
                                                    <td colspan="12" class="hiddenRow">
                                                        <div class="collapse" id="table-detail-{{ $i }}"
                                                            wire:ignore.self>
                                                            <table class="table table-striped">
                                                                <thead class="thead-dark">
                                                                    <tr class="info">
                                                                        <th>Dibayar</th>
                                                                        <th>Kurang</th>
                                                                        <th>Kembalian</th>
                                                                        <th>Tanggal Pembayaran</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (!empty($payments[$i]))
                                                                        @foreach ($payments[$i] as $item)
                                                                            <tr>
                                                                                <td>{{ idr($item['pay']) }}</td>
                                                                                <td>{{ idr($item['mines']) }}
                                                                                </td>
                                                                                <td> {{ idr($item['change']) }}
                                                                                </td>
                                                                                <td> {{ format_date($item['pay_date']) }}
                                                                                </td>
                                                                                <td style="width: 9rem;">
                                                                                    <button class="btn btn-dark btn-sm">
                                                                                        <i class="fa fa-print"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <x-modals.modal id="pay" title="Pembayaran">
            <form>
                <x-slot name="body">
                    <div class="form-group">
                        <x-inputs.text required id="pay_date" name="pay_date" class="datepicker" wire:model='pay_date'
                            label="tanggal bayar" />
                    </div>
                    <div class="form-group">
                        <x-inputs.number required name="pay" label="nominal" wire:model='pay' />
                    </div>
                    <div class="form-group">
                        <x-inputs.text name="mines" label="kurang" wire:model='mines' value="{{ idr($mines) }}"
                            disabled />
                    </div>
                    <div class="form-group">
                        <x-inputs.text name="change" label="kembalian" wire:model='change' value="{{ idr($change) }}"
                            disabled />
                    </div>
                </x-slot>
                <x-slot name="footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click.prevent='onPay' class="btn btn-primary">Bayar</button>
                </x-slot>
            </form>
        </x-modals.modal>
    </div>

    @push('styles')
        <link rel="stylesheet"
            href="https://demo.getstisla.com/assets/modules/bootstrap-daterangepicker/daterangepicker.css">
        <style type="text/css">
            .hiddenRow {
                height: 0 !important;
                padding: 0 !important;
            }

        </style>
    @endpush

    @push('scripts')
        <script src="https://demo.getstisla.com/assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", () => {
                Livewire.on("notify", () => {
                    $('#pay').modal('hide');
                });

                Livewire.on("pay", () => {
                    $('#pay').modal('toggle');
                });
            });

            $(document).ready(function() {
                if ($(".datepicker").length) {
                    $('.datepicker').daterangepicker({
                        locale: {
                            format: 'YYYY-MM-DD'
                        },
                        singleDatePicker: true,
                    });
                }

                $('#pay_date').on('change', (e) => {
                    @this.set('pay_date', e.target.value);
                });

                customSelect('#search-student', {
                    allowClear: false,
                    placeholder: 'Pilih Siswa'
                }, (e) => {
                    @this.set('student', e.target.value);
                });

                customSelect('#search-bill', {
                    allowClear: false,
                    placeholder: 'Pilih Tagihan'
                }, (e) => {
                    @this.set('bill', e.target.value);
                });

                customSelect('#search-year', {
                    allowClear: false,
                    placeholder: 'Pilih Tahun'
                }, (e) => {
                    @this.set('year', e.target.value);
                });
            });

        </script>
    @endpush
</div>
