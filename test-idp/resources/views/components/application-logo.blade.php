@if(App::getLocale() === 'de')
    <img
        src="https://www.stura-btu.de/curator/media/studierendenvertretung-logo.jpg?fm=webp&h=105&w=768&s=8391320acbaa52fd2ec176bdb08692e2"
        alt="Logo der Studierendenvertretung der BTU Cottbus-Senftenberg"
        {{ $attributes }}
    >
@else
    <img
        src="https://www.stura-btu.de/curator/media/student-representation-logo.jpg?fm=webp&h=105&w=768&s=afba52dc697781af744d686d779f0960"
        alt="Logo of the Student Representation of the BTU Cottbus-Senftenberg"
        {{ $attributes }}
    >
@endif
