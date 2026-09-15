import Link from 'next/link';

export default function Home() {
  return (
    <div className="min-h-screen flex items-center justify-center p-8 relative overflow-hidden">
      {/* Decorative background blobs */}
      <div className="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-pulse"></div>
      <div className="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-accent rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-pulse delay-1000"></div>

      <main className="glass-panel rounded-2xl p-12 max-w-3xl w-full relative z-10 flex flex-col items-center text-center shadow-2xl transition-transform hover:scale-[1.01] duration-300">
        <h1 className="text-5xl font-extrabold mb-6 bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
          Alondra Pro
        </h1>
        <p className="text-lg text-foreground/80 mb-8 max-w-xl">
          Tu planificador estudiantil inteligente impulsado por Inteligencia Artificial.
          Organiza, sincroniza y domina tus estudios con un flujo de trabajo de próxima generación.
        </p>

        <div className="flex gap-4">
          <Link 
            href="/dashboard"
            className="px-8 py-3 bg-primary text-primary-foreground font-semibold rounded-full hover:bg-primary/90 transition-all shadow-lg hover:shadow-primary/50"
          >
            Ir al Dashboard
          </Link>
          <button className="px-8 py-3 bg-transparent border border-glass-border font-semibold rounded-full hover:bg-secondary/50 transition-all text-foreground">
            Saber más
          </button>
        </div>
        
        {/* Placeholder for preview or stats */}
        <div className="mt-12 w-full grid grid-cols-1 sm:grid-cols-3 gap-6">
          {[
            { title: 'IA Planificador', value: 'Activado' },
            { title: 'Google Calendar', value: 'Sincronizado' },
            { title: 'Eventos', value: '14 próximos' }
          ].map((stat, i) => (
            <div key={i} className="bg-secondary/30 rounded-xl p-4 border border-glass-border">
              <div className="text-sm text-foreground/60 mb-1">{stat.title}</div>
              <div className="font-semibold text-accent">{stat.value}</div>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
