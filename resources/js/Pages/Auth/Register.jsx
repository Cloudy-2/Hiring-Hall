import { Head, Link, useForm } from "@inertiajs/react";
import { useState, useEffect, useRef } from "react";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        role:
            new URLSearchParams(window.location.search).get("role") ===
            "employer"
                ? "employer"
                : "applicant",
    });

    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const canvasRef = useRef(null);

    // Get role from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const roleParam = urlParams.get("role");
    const showBothRoles =
        !roleParam || (roleParam !== "applicant" && roleParam !== "employer");

    // Simple animated background effect
    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext("2d");
        let animationId;
        let time = 0;

        const resize = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
        resize();
        window.addEventListener("resize", resize);

        const animate = () => {
            time += 0.005;
            ctx.fillStyle = "#050510";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw subtle gradient orbs
            for (let i = 0; i < 3; i++) {
                const x = canvas.width * (0.3 + 0.2 * Math.sin(time + i * 2));
                const y =
                    canvas.height * (0.3 + 0.2 * Math.cos(time + i * 1.5));
                const gradient = ctx.createRadialGradient(x, y, 0, x, y, 300);
                gradient.addColorStop(
                    0,
                    `rgba(14, 165, 233, ${0.03 + 0.01 * Math.sin(time + i)})`,
                );
                gradient.addColorStop(1, "rgba(14, 165, 233, 0)");
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, canvas.width, canvas.height);
            }

            animationId = requestAnimationFrame(animate);
        };
        animate();

        return () => {
            window.removeEventListener("resize", resize);
            cancelAnimationFrame(animationId);
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route("register"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <>
            <Head title="Register — Hill Business Consulting Services" />

            <div
                className="text-white relative min-h-screen overflow-hidden bg-[#050510]"
                style={{ fontFamily: '"Rubik", sans-serif' }}
            >
                {/* Animated Background */}
                <div className="fixed top-0 left-0 w-screen h-screen z-0 pointer-events-none">
                    <canvas ref={canvasRef} className="w-full h-full block" />
                </div>

                {/* Split Layout */}
                <div className="min-h-screen flex items-center relative z-10 w-full">
                    {/* Back to Landing Button */}
                    <Link
                        href="/"
                        className="fixed top-5 left-5 z-50 flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-full backdrop-blur-sm transition-all group"
                    >
                        <svg
                            className="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Back to Home
                    </Link>

                    {/* LEFT: Branding Panel */}
                    <div className="hidden md:flex flex-col items-center justify-center w-1/2 h-full px-16 text-center">
                        <div className="animate-float-text">
                            <div className="flex flex-col items-center mb-10">
                                <div className="mb-4">
                                    <img
                                        className="h-32 w-auto object-contain drop-shadow-[0_0_25px_rgba(239,68,68,0.2)] animate-pulse-slow"
                                        src="/assets/logos/hiring-hall-full.png"
                                        alt="Logo"
                                        onError={(e) => {
                                            e.target.style.display = "none";
                                        }}
                                    />
                                </div>
                                <div className="flex flex-col items-center">
                                    <h1
                                        className="text-[52px] font-black tracking-tighter text-white uppercase leading-none"
                                        style={{
                                            fontFamily:
                                                "'Montserrat', sans-serif",
                                        }}
                                    >
                                        Hiring Hall
                                    </h1>
                                    <div className="flex items-center gap-3 mt-4">
                                        <div className="h-[1px] w-8 bg-gradient-to-r from-transparent to-sky-500/50"></div>
                                        <span
                                            className="text-[10px] font-black text-sky-400 uppercase tracking-[0.5em] opacity-80"
                                            style={{
                                                fontFamily:
                                                    "'Montserrat', sans-serif",
                                            }}
                                        >
                                            Hillbcs Powered
                                        </span>
                                        <div className="h-[1px] w-8 bg-gradient-to-l from-transparent to-sky-500/50"></div>
                                    </div>
                                </div>
                            </div>
                            <p className="mt-4 text-gray-300 text-base max-w-xs mx-auto leading-relaxed">
                                Create your account and start your journey with
                                us today.
                            </p>
                            <div className="mt-8 flex items-center justify-center gap-2 text-sky-400 text-sm font-medium">
                                <span className="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                <span>Secure</span>
                                <span className="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                <span>Trusted</span>
                                <span className="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                <span>Professional</span>
                            </div>
                        </div>
                    </div>

                    {/* RIGHT: Register Form (scrollable) */}
                    <div className="flex items-center justify-center w-full md:w-1/2 min-h-screen px-6 overflow-y-auto py-8">
                        <div className="w-full max-w-md my-auto">
                            {/* Mobile logo */}
                            <div className="md:hidden text-center mb-10 flex flex-col items-center">
                                <img
                                    className="h-24 w-auto object-contain drop-shadow-lg mb-4"
                                    src="/assets/logos/hiring-hall-full.png"
                                    alt="Logo"
                                    onError={(e) => {
                                        e.target.style.display = "none";
                                    }}
                                />
                                <div className="flex flex-col items-center">
                                    <span
                                        className="text-3xl font-black tracking-tighter text-white uppercase leading-none"
                                        style={{
                                            fontFamily:
                                                "'Montserrat', sans-serif",
                                        }}
                                    >
                                        Hiring Hall
                                    </span>
                                    <span
                                        className="text-[8px] font-black text-sky-400 uppercase tracking-[0.4em] mt-2 opacity-70"
                                        style={{
                                            fontFamily:
                                                "'Montserrat', sans-serif",
                                        }}
                                    >
                                        Hillbcs Powered
                                    </span>
                                </div>
                            </div>

                            <form
                                onSubmit={submit}
                                className="w-full space-y-4 bg-[#0a0f1c]/70 backdrop-blur-2xl border border-white/5 p-8 rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.6)] animate-form-breathe relative overflow-hidden"
                            >
                                <div className="absolute inset-0 bg-gradient-to-br from-white/[0.08] to-transparent pointer-events-none"></div>

                                <div className="relative z-10">
                                    <h2 className="text-2xl font-extrabold text-white tracking-tight drop-shadow-md">
                                        Create an Account
                                    </h2>
                                    <p className="mt-1 text-gray-300 text-sm">
                                        Join Hiring Hall and start your journey
                                        with us today.
                                    </p>
                                    <hr className="border-white/10 mt-4 mb-1" />
                                </div>

                                {/* Validation Errors */}
                                {Object.keys(errors).length > 0 && (
                                    <div className="flex items-start p-3 text-sm text-red-300 bg-red-900/40 border border-red-500/30 rounded-lg relative z-10">
                                        <svg
                                            className="w-4 h-4 mr-2 mt-0.5 shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            strokeWidth="2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <div>
                                            {Object.values(errors).map(
                                                (error, idx) => (
                                                    <div key={idx}>{error}</div>
                                                ),
                                            )}
                                        </div>
                                    </div>
                                )}

                                {/* Account Type */}
                                <div
                                    className={`grid ${showBothRoles ? "grid-cols-2" : "grid-cols-1"} gap-3 relative z-10`}
                                >
                                    {(showBothRoles ||
                                        roleParam === "applicant") && (
                                        <label
                                            htmlFor="role_candidate"
                                            className="cursor-pointer block"
                                        >
                                            <input
                                                type="radio"
                                                id="role_candidate"
                                                name="role"
                                                value="applicant"
                                                checked={
                                                    data.role === "applicant"
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "role",
                                                        e.target.value,
                                                    )
                                                }
                                                className="peer sr-only"
                                            />
                                            <div className="rounded-xl border border-white/10 bg-black/20 p-3 hover:bg-white/5 peer-checked:border-sky-500 peer-checked:bg-sky-500/10 peer-checked:ring-1 peer-checked:ring-sky-500/40 transition-all text-center backdrop-blur-sm">
                                                <svg
                                                    className="w-6 h-6 mx-auto mb-1 text-gray-400 peer-checked:text-sky-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    />
                                                </svg>
                                                <div className="font-semibold text-sm text-gray-300">
                                                    Applicant
                                                </div>
                                            </div>
                                        </label>
                                    )}
                                    {(showBothRoles ||
                                        roleParam === "employer") && (
                                        <label
                                            htmlFor="role_employer"
                                            className="cursor-pointer block"
                                        >
                                            <input
                                                type="radio"
                                                id="role_employer"
                                                name="role"
                                                value="employer"
                                                checked={
                                                    data.role === "employer"
                                                }
                                                onChange={(e) =>
                                                    setData(
                                                        "role",
                                                        e.target.value,
                                                    )
                                                }
                                                className="peer sr-only"
                                            />
                                            <div className="rounded-xl border border-white/10 bg-black/20 p-3 hover:bg-white/5 peer-checked:border-sky-500 peer-checked:bg-sky-500/10 peer-checked:ring-1 peer-checked:ring-sky-500/40 transition-all text-center backdrop-blur-sm">
                                                <svg
                                                    className="w-6 h-6 mx-auto mb-1 text-gray-400 peer-checked:text-sky-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                    />
                                                </svg>
                                                <div className="font-semibold text-sm text-gray-300">
                                                    Employer
                                                </div>
                                            </div>
                                        </label>
                                    )}
                                </div>

                                {/* Name */}
                                <div className="relative z-10">
                                    <label
                                        htmlFor="name"
                                        className="block text-sm font-medium text-gray-200 mb-1"
                                    >
                                        Full Name
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData("name", e.target.value)
                                        }
                                        required
                                        autoFocus
                                        autoComplete="name"
                                        placeholder="John Doe"
                                        className="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm"
                                    />
                                </div>

                                {/* Email */}
                                <div className="relative z-10">
                                    <label
                                        htmlFor="email"
                                        className="block text-sm font-medium text-gray-200 mb-1"
                                    >
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value={data.email}
                                        onChange={(e) =>
                                            setData("email", e.target.value)
                                        }
                                        required
                                        autoComplete="username"
                                        placeholder="name@company.com"
                                        className="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm"
                                    />
                                </div>

                                {/* Password */}
                                <div className="relative z-10">
                                    <label
                                        htmlFor="password"
                                        className="block text-sm font-medium text-gray-200 mb-1"
                                    >
                                        Password
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={
                                                showPassword
                                                    ? "text"
                                                    : "password"
                                            }
                                            id="password"
                                            name="password"
                                            value={data.password}
                                            onChange={(e) =>
                                                setData(
                                                    "password",
                                                    e.target.value,
                                                )
                                            }
                                            required
                                            autoComplete="new-password"
                                            placeholder="*************"
                                            className="block w-full px-4 py-2 pr-10 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm"
                                        />
                                        <span
                                            onClick={() =>
                                                setShowPassword(!showPassword)
                                            }
                                            className="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                        >
                                            {showPassword ? (
                                                <svg
                                                    className="w-5 h-5 text-gray-400 hover:text-white transition-colors"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                                    />
                                                </svg>
                                            ) : (
                                                <svg
                                                    className="w-5 h-5 text-gray-400 hover:text-white transition-colors"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />
                                                </svg>
                                            )}
                                        </span>
                                    </div>
                                </div>

                                {/* Confirm Password */}
                                <div className="relative z-10">
                                    <label
                                        htmlFor="password_confirmation"
                                        className="block text-sm font-medium text-gray-200 mb-1"
                                    >
                                        Confirm Password
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={
                                                showConfirmPassword
                                                    ? "text"
                                                    : "password"
                                            }
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            value={data.password_confirmation}
                                            onChange={(e) =>
                                                setData(
                                                    "password_confirmation",
                                                    e.target.value,
                                                )
                                            }
                                            required
                                            autoComplete="new-password"
                                            placeholder="*************"
                                            className="block w-full px-4 py-2 pr-10 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm"
                                        />
                                        <span
                                            onClick={() =>
                                                setShowConfirmPassword(
                                                    !showConfirmPassword,
                                                )
                                            }
                                            className="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                                        >
                                            {showConfirmPassword ? (
                                                <svg
                                                    className="w-5 h-5 text-gray-400 hover:text-white transition-colors"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                                    />
                                                </svg>
                                            ) : (
                                                <svg
                                                    className="w-5 h-5 text-gray-400 hover:text-white transition-colors"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />
                                                </svg>
                                            )}
                                        </span>
                                    </div>
                                </div>

                                {/* Submit */}
                                <div className="pt-1 relative z-10">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400 transition-all transform active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                                        style={{ letterSpacing: "0.5px" }}
                                    >
                                        {processing
                                            ? "Creating account..."
                                            : "Create your account"}
                                    </button>
                                </div>

                                {/* Already Registered */}
                                <p className="text-center text-sm text-gray-300 relative z-10">
                                    Already have an account?{" "}
                                    <Link
                                        href={route("login")}
                                        className="font-medium text-sky-400 hover:text-sky-300 transition-all"
                                    >
                                        Sign in
                                    </Link>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@800;900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

                @keyframes form-breathe {
                    0%, 100% { box-shadow: 0 8px 32px 0 rgba(0,0,0,0.6), 0 0 0px rgba(14,165,233,0); border-color: rgba(255,255,255,0.05); }
                    50% { box-shadow: 0 8px 32px 0 rgba(0,0,0,0.6), 0 0 30px rgba(14,165,233,0.15); border-color: rgba(14,165,233,0.25); }
                }
                .animate-form-breathe { animation: form-breathe 6s ease-in-out infinite; }

                @keyframes float-text {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-6px); }
                }
                .animate-float-text { animation: float-text 7s ease-in-out infinite; }

                @keyframes pulse-slow {
                    0%, 100% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(14,165,233,0.3)); }
                    50% { transform: scale(1.04); filter: drop-shadow(0 0 28px rgba(14,165,233,0.7)); }
                }
                .animate-pulse-slow { animation: pulse-slow 5s ease-in-out infinite; }

                /* Custom scrollbar */
                .form-scroll::-webkit-scrollbar { width: 3px; }
                .form-scroll::-webkit-scrollbar-track { background: transparent; }
                .form-scroll::-webkit-scrollbar-thumb { background: rgba(14,165,233,0.3); border-radius: 2px; }

                /* Autofill overrides for dark inputs */
                input:-webkit-autofill,
                input:-webkit-autofill:hover,
                input:-webkit-autofill:focus,
                input:-webkit-autofill:active {
                    -webkit-box-shadow: 0 0 0 30px #040712 inset !important;
                    -webkit-text-fill-color: white !important;
                }
            `}</style>
        </>
    );
}
