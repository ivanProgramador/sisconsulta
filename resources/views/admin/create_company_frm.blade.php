<x-layouts.auth-layout subtitle="{{ $subtitle }}">

    <div class="main-card overflow-auto">

    <div class="flex justify-between">
        <p class="title-3">Novo cliente</p>
        <a href="#" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
    </div>

    <hr class="my-4">

    <form action="#" method="POST" enctype="multipart/form-data">

        <div class="flex gap-6">

            {{-- company logo --}}
            <div class="main-card w-1/3">
                <label for="company_logo" class="label">Logo do cliente</label>
                <input type="file" name="company_logo" id="company_logo" class="input" />
            </div>

            <div class="main-card w-2/3">

                <div class="mb-4">
                    <label for="company_name" class="label">Nome do cliente</label>
                    <input type="text" name="company_name" id="company_name" class="input w-full" />
                </div>

                <div class="mb-4">
                    <label for="address" class="label">Endereço</label>
                    <input type="text" name="address" id="address" class="input w-full" />
                </div>

                <div class="flex gap-4 mb-4">

                    <div class="w-1/3">
                        <label for="phone" class="label">Telefone</label>
                        <input type="text" name="phone" id="phone" class="input w-full" />
                    </div>

                    <div class="w-2/3">
                        <label for="email" class="label">Email</label>
                        <input type="email" name="email" id="email" class="input w-full" />
                    </div>

                </div>

                <div class="mb-4">

                    <label for="status" class="label">Estado</label>
                    <select class="input w-1/3">
                        <option value="active" selected>Ativo</option>
                        <option value="inactive">Inativo</option>
                    </select>

                </div>

                <hr class="my-6">

                <div class="w-2/3 mb-4">
                    <p class="text-2xl text-slate-900">Defina o usuário administrador.</p>
                    <p class="text-sm text-slate-400 mb-4">Será enviado um email com as instruções para a conclusão do registo.</p>
                    <label for="admin_email" class="label">Email do administrador cliente</label>
                    <input type="email" name="admin_email" id="admin_email" class="input w-full" />
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Criar cliente</button>

            </div>

        </div>

    </form>

</div>


</x-layouts.auth-layout>
