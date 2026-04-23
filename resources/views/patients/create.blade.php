<x-app-layout>
    <section class="px-4 py-6">
        <div class="mx-auto w-full max-w-4xl rounded-[28px] border border-[#d8e0eb] bg-white p-6 shadow-[0_20px_60px_rgba(15,23,42,0.10)] md:p-8">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-[#172033] md:text-[28px]">Register New Patient</h1>
                    <p class="mt-1 text-sm text-[#6b7280] md:text-base">Enter patient details to create a new record.</p>
                </div>
                <a href="{{ route('patients.index') }}" class="flex h-9 w-9 items-center justify-center rounded-full text-[#6b7280] transition hover:bg-[#f3f6fb] hover:text-[#172033]" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <form method="POST" action="{{ route('patients.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Gender</label>
                        <select name="gender" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#6b7280] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]">
                            <option value="">Select gender</option>
                            <option value="male" @selected(old('gender') === 'male')>Male</option>
                            <option value="female" @selected(old('gender') === 'female')>Female</option>
                            <option value="other" @selected(old('gender') === 'other')>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Address" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Blood Type (Optional)</label>
                        <input type="text" name="blood_type" value="{{ old('blood_type') }}" placeholder="e.g. A+" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#172033]">Allergies (Optional)</label>
                        <input type="text" name="allergies" value="{{ old('allergies') }}" placeholder="Comma separated" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                    </div>
                </div>

                <div class="border-t border-[#e5e9f1] pt-5">
                    <h2 class="text-lg font-semibold text-[#172033]">Emergency Contact</h2>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-[#172033]">Name</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Emergency contact name" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-[#172033]">Phone</label>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="Contact phone" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-[#172033]">Relationship</label>
                            <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" placeholder="Relationship" class="w-full rounded-xl border border-[#e5e9f1] bg-[#f6f7fb] px-4 py-3 text-sm text-[#172033] placeholder:text-[#8a94a6] focus:border-[#2463eb] focus:ring-2 focus:ring-[#d9e7ff]" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('patients.index') }}" class="rounded-xl border border-[#d7deea] bg-white px-5 py-3 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">Cancel</a>
                    <button type="submit" class="rounded-xl bg-[#2463eb] px-5 py-3 text-sm font-medium text-white hover:bg-[#1f54c9]">Register Patient</button>
                </div>
            </form>
        </div>
    </section>

</x-app-layout>
