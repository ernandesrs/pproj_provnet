@if ($filter ?? null)
    <div class="row py-2 mb-3">
        <div class="col-12">
            <form class="jsFormSubmit" action="{{ $filter }}" method="post">
                <div class="message-area jsMessageArea"></div>

                <div class="form-row justify-content-center justify-content-md-end">
                    @if ($filterGroup == 'users')
                        <div class="col-12 col-sm-4 col-md-2">
                            <label for="status" class="sr-only">Status</label>
                            <select class="form-control text-center" name="status" id="status">
                                <option value="all">Todos</option>
                                @foreach (\App\Models\User::ALLOWED_STATUS as $status)
                                    <option value="{{ $status }}"
                                        {{ Request::input('status', null) == $status ? 'selected' : null }}>
                                        {{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-3">
                        <div class="form-group d-flex mb-0">
                            <label for="search" class="sr-only">Pesquisar</label>
                            <input class="form-control text-center" type="text" name="search" id="search"
                                value="{{ Request::input('search', '') }}" placeholder="Buscar por algo...">
                            <button class="btn bg-transparent" type="submit">
                                {{ icon('filter') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
