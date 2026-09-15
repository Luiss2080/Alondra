import { render, screen } from '@testing-library/react';
import EventModal from '@/components/EventModal';
import Calendar from '@/components/Calendar';

describe('EventModal Component', () => {
  it('renders correctly when isOpen is true', () => {
    render(<EventModal isOpen={true} onClose={() => {}} />);
    expect(screen.getByText('Nuevo Evento')).toBeInTheDocument();
  });

  it('does not render when isOpen is false', () => {
    render(<EventModal isOpen={false} onClose={() => {}} />);
    expect(screen.queryByText('Nuevo Evento')).not.toBeInTheDocument();
  });
});

describe('Calendar Component', () => {
  it('renders the calendar with mock events', () => {
    render(<Calendar />);
    expect(screen.getByText('Mi Calendario')).toBeInTheDocument();
    expect(screen.getByText('Estudiar Álgebra')).toBeInTheDocument();
  });
});
