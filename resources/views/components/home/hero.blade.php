@props(['imageUrl','subtitle'])
<div class="hero h-s relative z-0" style="background-image: url('{{$imageUrl}}');">
    <div
        class="bg-black/70  w-full overflow-hidden pt-[50px] pb-[100px] md:pt-35 md:pb-[170px] lg:pt-[100px] lg:pb-[150px] xl:pt-35 xl:pb-[170px]">
        <div class="container mx-auto  xl:max-w-[85.625rem] xl:my-0 xl:mx-auto relative overflow-hidden text-center">
            <div data-aos="fade-up">
                <div>
                    <h3 class="uppercase text-primary text-[1.1rem] mb-6 md:mb-[15px] font-inter tracking-[4px] rtl:tracking-[1px] font-semibold">
                        {{$subtitle}}
                    </h3>
                        {{$slot}}
                    <div class=" mb-24 md:mb-[50px]">
                        <a href="#"
                            class="text-white bg-primary-500  border border-primary-500
                            px-10 py-[10px] hover:text-primary-500 hover:bg-white rounded-full
                            inline-block dark:hover:bg-[#0C0E2B] dark:hover:text-white transition-colors duration-300">
                            {{t('Contact US')}}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
