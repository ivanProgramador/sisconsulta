<x-layouts.auth-layout subtitle="{{ $subtitle }}">

    <div class="main-card overflow-auto">
        <p class="title-2">Eliminar fila de espera !</p>
        <hr class="my-4">
        <p class="text-slate-600 mb-4 text-center">Tem certeza que deseja apagar a fila de espera ?</p>
        <p class="text-lg text-zinc-600 font-bold mb-4 text-center">{{ $queue->name }}</p>
        <p class="text-lg text-zinc-600 font-bold mb-4 text-center">{{ $queue->hash_code }}</p>
        <p class="text-lg text-zinc-600 font-bold mb-6 text-center">Essa operação é reversivel</p>
        <div class="flex justify-center gap-4">

            <a href="{{ route('queue.delete.confirm',['id' => Crypt::encrypt($queue->id)]) }}" class="btn !px-8">Sim</a>
            <a href="{{ route('home') }}" class="btn-red !px-8 ">Não</a>

        </div>

    </div>
</x-layouts.auth-layout>
