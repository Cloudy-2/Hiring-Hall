import { Head, Link } from "@inertiajs/react";

export default function LandingPage({ stats, canLogin, canRegister, auth }) {
    return (
        <>
            <Head title="Hiring Hall — Hill Business Consulting Services" />

            <div className="min-h-screen bg-gray-50">
                {/* Top Bar */}
                <div className="bg-white border-b border-gray-200 px-6 py-3">
                    <div className="max-w-7xl mx-auto flex justify-between items-center text-sm">
                        <span className="text-gray-700">
                            Ready to hire or get hired?{" "}
                            <strong className="text-gray-900">
                                Get Started →
                            </strong>
                        </span>
                        <div className="flex gap-6">
                            <a
                                href="mailto:support@hillbcs.com"
                                className="text-gray-600 hover:text-gray-900"
                            >
                                support@hillbcs.com
                            </a>
                            <a
                                href="tel:+12797909952"
                                className="text-gray-600 hover:text-gray-900"
                            >
                                (+1) 279 790 9952
                            </a>
                        </div>
                    </div>
                </div>

                {/* Navbar */}
                <nav className="bg-white border-b border-gray-200 sticky top-0 z-50">
                    <div className="max-w-7xl mx-auto px-6 h-18 flex items-center justify-between">
                        <Link href="/" className="flex items-center gap-3">
                            <div className="w-11 h-11 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-xl">
                                    H
                                </span>
                            </div>
                            <span className="text-xl font-bold text-gray-900">
                                Hiring Hall
                            </span>
                        </Link>

                        <div className="flex items-center gap-2">
                            {auth?.user ? (
                                <Link
                                    href="/dashboard"
                                    className="px-6 py-2.5 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    {canLogin && (
                                        <Link
                                            href="/login"
                                            className="px-6 py-2.5 text-gray-700 hover:text-gray-900 font-semibold transition"
                                        >
                                            Sign In
                                        </Link>
                                    )}
                                    {canRegister && (
                                        <Link
                                            href="/register"
                                            className="px-6 py-2.5 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition"
                                        >
                                            Get Started
                                        </Link>
                                    )}
                                </>
                            )}
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <section className="relative bg-gradient-to-br from-gray-900 to-gray-800 text-white py-24">
                    <div className="max-w-7xl mx-auto px-6 text-center">
                        <p className="text-yellow-400 font-semibold text-sm uppercase tracking-wider mb-4">
                            Hiring Hall by Hill Business Consulting Services
                        </p>
                        <h1 className="text-5xl md:text-6xl font-extrabold mb-6">
                            Your Virtual
                            <br />
                            Talent Hub
                        </h1>
                        <p className="text-xl text-gray-300 max-w-2xl mx-auto mb-10">
                            The premier platform connecting employers with
                            top-tier virtual assistant talent. Post jobs,
                            discover candidates, and build your dream team.
                        </p>
                        <div className="flex gap-4 justify-center flex-wrap">
                            {auth?.user ? (
                                <Link
                                    href="/dashboard"
                                    className="px-8 py-4 bg-white text-gray-900 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-lg"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href="/register?role=employer"
                                        className="px-8 py-4 bg-white text-gray-900 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-lg"
                                    >
                                        I'm an Employer
                                    </Link>
                                    <Link
                                        href="/register?role=applicant"
                                        className="px-8 py-4 bg-gray-800 text-white border-2 border-white/20 rounded-xl font-bold text-lg hover:bg-gray-700 transition"
                                    >
                                        I'm an Applicant
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </section>

                {/* Stats Section */}
                <section className="bg-gray-900 text-white py-16">
                    <div className="max-w-5xl mx-auto px-6">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                            <div>
                                <div className="text-5xl font-extrabold text-yellow-400 mb-2">
                                    {stats.totalJobs}+
                                </div>
                                <div className="text-gray-400 text-lg">
                                    Open Positions
                                </div>
                            </div>
                            <div>
                                <div className="text-5xl font-extrabold text-yellow-400 mb-2">
                                    {stats.totalApplicants}+
                                </div>
                                <div className="text-gray-400 text-lg">
                                    Applicants
                                </div>
                            </div>
                            <div>
                                <div className="text-5xl font-extrabold text-yellow-400 mb-2">
                                    {stats.totalHires}+
                                </div>
                                <div className="text-gray-400 text-lg">
                                    Successful Matches
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* How It Works */}
                <section className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-6">
                        <div className="text-center mb-16">
                            <span className="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold uppercase tracking-wide mb-4">
                                Simple Process
                            </span>
                            <h2 className="text-4xl font-extrabold text-gray-900 mb-4">
                                How It Works
                            </h2>
                            <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                                Getting started takes just minutes — whether
                                you're hiring or looking for work.
                            </p>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {[
                                {
                                    step: "1",
                                    title: "Create Your Account",
                                    description:
                                        "Sign up as an employer or applicant. Set up your profile in minutes.",
                                },
                                {
                                    step: "2",
                                    title: "Post or Apply",
                                    description:
                                        "Employers post job openings. Applicants browse listings and apply.",
                                },
                                {
                                    step: "3",
                                    title: "Hire & Get Hired",
                                    description:
                                        "Review applications, schedule interviews, and make the perfect match.",
                                },
                            ].map((item) => (
                                <div key={item.step} className="text-center">
                                    <div className="relative inline-flex items-center justify-center w-24 h-24 bg-white border-2 border-gray-200 rounded-2xl mb-6 shadow-sm hover:shadow-md transition">
                                        <span className="text-3xl">📋</span>
                                        <span className="absolute -top-2 -right-2 w-8 h-8 bg-gray-900 text-yellow-400 rounded-full flex items-center justify-center text-sm font-bold">
                                            {item.step}
                                        </span>
                                    </div>
                                    <h3 className="text-xl font-bold text-gray-900 mb-3">
                                        {item.title}
                                    </h3>
                                    <p className="text-gray-600">
                                        {item.description}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="py-20 bg-gray-50">
                    <div className="max-w-4xl mx-auto px-6">
                        <div className="bg-white rounded-3xl p-12 shadow-xl border border-gray-200 text-center">
                            <h2 className="text-4xl font-extrabold text-gray-900 mb-4">
                                Ready to Get Started?
                            </h2>
                            <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                                Join Hiring Hall today — whether you're looking
                                to hire virtual assistants or seeking your next
                                opportunity.
                            </p>
                            <div className="flex gap-4 justify-center flex-wrap">
                                {auth?.user ? (
                                    <Link
                                        href="/dashboard"
                                        className="px-8 py-4 bg-yellow-500 text-gray-900 rounded-xl font-bold text-lg hover:bg-yellow-400 transition shadow-lg"
                                    >
                                        Go to Dashboard
                                    </Link>
                                ) : (
                                    <>
                                        <Link
                                            href="/register?role=employer"
                                            className="px-8 py-4 bg-yellow-500 text-gray-900 rounded-xl font-bold text-lg hover:bg-yellow-400 transition shadow-lg"
                                        >
                                            Register as Employer
                                        </Link>
                                        <Link
                                            href="/register?role=applicant"
                                            className="px-8 py-4 bg-gray-900 text-white rounded-xl font-bold text-lg hover:bg-gray-800 transition shadow-lg"
                                        >
                                            Register as Applicant
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="bg-gray-100 py-12">
                    <div className="max-w-7xl mx-auto px-6">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <div className="flex items-center gap-3 mb-4">
                                    <div className="w-11 h-11 bg-yellow-500 rounded-lg flex items-center justify-center">
                                        <span className="text-white font-bold text-xl">
                                            H
                                        </span>
                                    </div>
                                    <span className="text-xl font-bold text-gray-900">
                                        Hiring Hall
                                    </span>
                                </div>
                                <p className="text-gray-600 text-sm mb-4">
                                    Hiring Hall connects employers with vetted
                                    virtual assistant talent.
                                </p>
                            </div>
                            <div>
                                <h3 className="font-bold text-gray-900 mb-4">
                                    Contact
                                </h3>
                                <p className="text-gray-600 text-sm mb-2">
                                    <a
                                        href="tel:+12797909952"
                                        className="hover:text-gray-900"
                                    >
                                        (+1) 279 790 9952
                                    </a>
                                </p>
                                <p className="text-gray-600 text-sm">
                                    <a
                                        href="mailto:support@hillbcs.com"
                                        className="hover:text-gray-900"
                                    >
                                        support@hillbcs.com
                                    </a>
                                </p>
                            </div>
                            <div>
                                <h3 className="font-bold text-gray-900 mb-4">
                                    Location
                                </h3>
                                <p className="text-gray-600 text-sm">
                                    Sacramento, California
                                </p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
