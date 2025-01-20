<x-app-layout>
    <main>
        <section>
            <div class="container">
                <h2>My Favourite Cars</h2>
                <div class="car-items-listing">
                    @foreach($cars as $car)
                        <x-car-item :car="$car" :isInWatchlist="true"/>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
