<x-layouts.auth-layout subtitle="{{ $subtitle }}">

    <div class="main-card overflow-auto">

        <p class="text-3xl font-bold text-center text-green-700 my-6">Novo cliente adicionado com sucesso !</p>

        <p>{{ $company_name }}</p>
        <p>Foi enviado um e-mail para:<strong>{{ $admin_email }}</strong>com o link para conclusão de regsitro </p>

        <div class="text-center">
            <a href="#" class="btn"><i class="fa-solid fa-check me-2"></i>Voltar</a>
        </div>
    </div>
</x-layouts.auth-layout>