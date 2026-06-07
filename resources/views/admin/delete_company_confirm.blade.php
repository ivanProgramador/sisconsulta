<x-layouts.auth-layout subtitle="{{ $subtitle }}">
    <div class="flex justify-center">
        <div class="main-card w-200 mt-12">
            <div class="text-center p-6">
                <i class="fa-solid fa-triangle-exclamation text-6xl text-red-600 mb-4"></i>
                <p class="title-2 mb-4">Confirmar Elimnação do Cliente</p>
                <p class="mb-4" >Tem certeza que deseja apagar o Cadastro do cliente <strong> {{$company->name}} </strong> ? </p>
                <p class="text-sm text-slate-100 mb-6">Essa operação é reversivel</p>

                <div class="flex justify-center gap-4">
                    <a href="{ route('admin.home') }" class="btn">Cancelar</a>
                     <a href="{{ route('admin.company.delete.confirm',['id'=>Crypt::encrypt($company->id)]) }}" class="btn-red">Confirmar</a>
                </div>
            </div>
        </div>
    </div>





</x-layouts.auth-layout>
