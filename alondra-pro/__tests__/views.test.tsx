import { render, screen } from '@testing-library/react';
import PerformancePage from '@/app/dashboard/performance/page';
import SettingsPage from '@/app/dashboard/settings/page';
import AIPlanner from '@/app/dashboard/ai-planner/page';

describe('Rutas y Vistas (Views)', () => {
  it('debería renderizar la página de Rendimiento correctamente', () => {
    render(<PerformancePage />);
    expect(screen.getByText('Rendimiento Académico')).toBeInTheDocument();
    expect(screen.getByText('Horas Estudiadas')).toBeInTheDocument();
  });

  it('debería renderizar el Planificador IA', () => {
    render(<AIPlanner />);
    expect(screen.getByText('IA Planificador')).toBeInTheDocument();
  });

  it('debería renderizar los Ajustes con la opción de Sincronización', () => {
    render(<SettingsPage />);
    expect(screen.getByText('Sincronización')).toBeInTheDocument();
  });
});
