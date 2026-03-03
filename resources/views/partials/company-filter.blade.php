{{-- Company filter dropdown for superadmin --}}
@if(auth()->user()->isSuperAdmin())
    <select name="company_id"
        class="px-4 py-2 bg-gray-100 dark:bg-dark-800/50 border border-gray-300 dark:border-dark-600/50 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
        <option value="">Semua Perusahaan</option>
        @foreach(\App\Models\Company::all() as $comp)
            <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
        @endforeach
    </select>
@endif