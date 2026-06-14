import React, { useEffect, useState } from 'react';
import { Sun, Moon, Palette } from 'lucide-react';

export default function ThemeToggle() {
  // themes: 'blue' (initial colors), 'orange' (second colors), 'dark'
  const [theme, setTheme] = useState(() => {
    if (typeof window !== 'undefined') {
      const storedTheme = localStorage.getItem('theme');
      if (storedTheme) {
        return storedTheme;
      }
      return 'blue'; 
    }
    return 'blue';
  });

  useEffect(() => {
    const root = window.document.documentElement;
    
    // Remove all classes/attributes
    root.classList.remove('dark');
    root.removeAttribute('data-theme');
    
    if (theme === 'dark') {
      root.classList.add('dark');
    } else if (theme === 'orange') {
      root.setAttribute('data-theme', 'orange');
    } else {
      root.setAttribute('data-theme', 'blue');
    }
    
    localStorage.setItem('theme', theme);
  }, [theme]);

  const cycleTheme = () => {
    if (theme === 'blue') setTheme('orange');
    else if (theme === 'orange') setTheme('dark');
    else setTheme('blue');
  };

  return (
    <button
      onClick={cycleTheme}
      title={`Toggle Theme (Current: ${theme})`}
      className="flex size-14 rounded-full bg-monday-gray-background items-center justify-center overflow-hidden hover:opacity-80 transition-300 shrink-0 cursor-pointer text-monday-black border border-monday-border"
    >
      {theme === 'blue' && <Sun size={20} />}
      {theme === 'orange' && <Palette size={20} />}
      {theme === 'dark' && <Moon size={20} />}
    </button>
  );
}
