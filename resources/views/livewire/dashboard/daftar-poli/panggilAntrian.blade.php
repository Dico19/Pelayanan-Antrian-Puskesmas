<!-- Modal Panggil Antrian -->
<div wire:ignore.self class="modal fade" id="panggilAntrian" tabindex="-1"
     aria-labelledby="panggilAntrianLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="panggilAntrianLabel">Panggil Antrian</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        wire:click="$set('selectedId', null)" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p>Yakin ingin memanggil antrian ini?</p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        wire:click="$set('selectedId', null)">
                    Kembali
                </button>

                <button type="button"
                        class="btn btn-primary"
                        wire:click="panggilAntrian">
                    Ya, Panggil
                </button>
            </div>

        </div>
    </div>
</div>
