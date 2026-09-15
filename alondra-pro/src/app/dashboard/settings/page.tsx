"use client";

import React, { useState, useEffect } from 'react';
import { Settings, Moon, Sun, Monitor, Cloud } from 'lucide-react';
import { motion } from 'framer-motion';

export default function SettingsPage() {
  const [theme, setTheme] = useState<'dark' | 'light'>('dark');

  // Simple theme toggle logic
  useEffect(() => {
    if (theme === 'light') {
      document.documentElement.classList.add('light-theme');
      // For a real app, you'd swap CSS variables or add Tailwind's dark class
    } else {
      document.documentElement.classList.remove('light-theme');
    }
  }, [theme]);

  return (
    <div className="flex h-full flex-col relative">
      <header className="mb-8">
        <h1 className="text-3xl font-bold flex items-center gap-3">
          <Settings className="text-primary" size={32} />
          Ajustes
        </h1>
        <p className="text-foreground/60 text-sm mt-1">Configura tus preferencias y apariencia del sistema.</p>
      </header>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
        
        {/* Theme Settings */}
        <motion.section 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          className="glass-panel p-6 rounded-3xl border border-glass-border"
        >
          <h2 className="text-xl font-semibold mb-4 flex items-center gap-2">
            <Monitor size={20} />
            Apariencia
          </h2>
          
          <div className="flex gap-4">
            <button 
              onClick={() => setTheme('dark')}
              className={`flex-1 flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all ${theme === 'dark' ? 'border-primary bg-primary/10' : 'border-glass-border hover:bg-secondary/50'}`}
            >
              <Moon size={24} className={theme === 'dark' ? 'text-primary' : 'text-foreground/50'} />
              <span className="font-medium text-sm">Modo Oscuro</span>
            </button>
            <button 
              onClick={() => setTheme('light')}
              className={`flex-1 flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all ${theme === 'light' ? 'border-accent bg-accent/10' : 'border-glass-border hover:bg-secondary/50'}`}
            >
              <Sun size={24} className={theme === 'light' ? 'text-accent' : 'text-foreground/50'} />
              <span className="font-medium text-sm">Modo Claro</span>
            </button>
          </div>
        </motion.section>

        {/* Sync Settings */}
        <motion.section 
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.1 }}
          className="glass-panel p-6 rounded-3xl border border-glass-border"
        >
          <h2 className="text-xl font-semibold mb-4 flex items-center gap-2">
            <Cloud size={20} />
            Sincronización
          </h2>
          <div className="bg-secondary/30 p-4 rounded-2xl border border-glass-border flex justify-between items-center">
            <div>
              <h3 className="font-bold mb-1">Google Calendar</h3>
              <p className="text-xs text-foreground/60">Sincroniza tus eventos en tiempo real.</p>
            </div>
            <button className="px-4 py-2 bg-primary/20 text-primary hover:bg-primary hover:text-white transition-colors rounded-xl font-semibold text-sm">
              Conectar
            </button>
          </div>
        </motion.section>

      </div>
    </div>
  );
}
