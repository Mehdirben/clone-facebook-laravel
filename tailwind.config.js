import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Helvetica Neue', 'Arial', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                facebook: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#1877f2',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                background: {
                    light: '#f0f2f5',
                    dark: '#18191a',
                    card: '#ffffff',
                    'card-dark': '#242526',
                    hover: '#f2f3f5',
                    'hover-dark': '#3a3b3c',
                },
                text: {
                    primary: '#1c1e21',
                    'primary-dark': '#e4e6ea',
                    secondary: '#65676b',
                    'secondary-dark': '#b0b3b8',
                    muted: '#8a8d91',
                    'muted-dark': '#8a8d91',
                },
                border: {
                    light: '#e4e6ea',
                    dark: '#3e4042',
                }
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            borderRadius: {
                'xl': '12px',
                '2xl': '16px',
                '3xl': '20px',
            },
            boxShadow: {
                'facebook': '0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1)',
                'facebook-hover': '0 8px 24px rgba(0, 0, 0, 0.15)',
                'facebook-dark': '0 2px 4px rgba(0, 0, 0, 0.3), 0 8px 16px rgba(0, 0, 0, 0.3)',
                'glow': '0 0 20px rgba(24, 119, 242, 0.3)',
            },
            animation: {
                'bounce-slow': 'bounce 2s infinite',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'wiggle': 'wiggle 1s ease-in-out infinite',
                'float': 'float 3s ease-in-out infinite',
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%': { transform: 'rotate(3deg)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
            backdropBlur: {
                xs: '2px',
            }
        },
    },

    plugins: [forms],
};
