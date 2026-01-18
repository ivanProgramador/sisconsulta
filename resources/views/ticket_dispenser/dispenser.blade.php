<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-2 h-full">

        <div class="text-end mb-4">
            [opções]
        </div>

        <div class="main-card flex gap-4 w-full">

            <div class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl">

                <div class="w-1/3 p-2">
                    <div class="main-card">
                         <x-dispenser-queue /> 
                    </div>
                </div>

                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>

                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>
                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>
                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>
                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>
                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>
                <div class="w-1/3 p-2">
                    <div class="main-card">
                        <x-dispenser-queue /> 
                    </div>
                </div>
                  
            </div>

            
                <div class="flex w-1/3 h-100 border-1 border-slate-300 rounded-xl p-2">
                     [preview do ticket]
                </div>
                     
        
        </div>





    </div>

</x-layouts.guest-layout>