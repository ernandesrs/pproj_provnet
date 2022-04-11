<div class="modal confirmation-modal fade jsConfirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="jsFormSubmit" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="message-area jsMessageArea"></div>

                    <div class="modal-text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-close" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-confirm">
                        {{ icon('check.lg') }}
                        <span>Confirmar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
