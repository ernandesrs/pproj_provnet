<div id="modalUserBan" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form class="jsFormSubmit" action="{{ route('admin.users.ban', ['id' => $user->id]) }}" method="post">
                    <div class="jsMessageArea"></div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="type">Tipo:</label>
                            <div class="form-group">
                                <select id="type" class="form-control" name="type">
                                    @foreach (\App\Models\Ban::ALLOWED_TYPES as $type)
                                        <option value="{{ $type }}">
                                            {{ $type == 'temp' ? 'Temporário' : 'Permanente' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="days">Dias:</label>
                                <input class="form-control" type="number" name="days" id="days">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea class="form-control" id="description" class="form-control"
                                    name="description"></textarea>
                            </div>
                        </div>

                        <div class="col-12 py-2 text-center">
                            <button class="btn btn-sm btn-warning" type="submit">
                                {{ icon('user.x') }} Banir
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
