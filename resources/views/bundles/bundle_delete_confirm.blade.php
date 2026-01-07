<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
  <div class="main-card overflow-auto">
     <p class="title-2"> Eliminar bundle</p>
     <hr class="my-4">

      <div class="text-center">
        <p class="text-slate-600 mb-4">Tem certeza que deseja apagar esse grupo</p>
        <p class="text-lg text-zinc-600 font-bold">{{ $bundle->name }}</p>
        <p class="text-sm text-slate-400 mb-6">Esta operação é reversivel</p>
      </div>

      <div class="flex justify-center gap-4">

         <a href="{{ route('bundles.home') }}" class="btn !px-8" >Não</a>
         <a href="{{ route('bundle.delete.confirm',['id'=>Crypt::encrypt($bundle->id)]) }}" class="btn-red !px-8" >Sim</a>

      </div>

  </div>

</x-layouts.auth-layout>