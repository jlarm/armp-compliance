export function usePhoneFormatter() {
    const formatPhoneNumber = (value: string): string => {
        const cleaned = value.replace(/\D/g, '');

        if (cleaned.length === 0) return '';
        if (cleaned.length <= 3) return cleaned;
        if (cleaned.length <= 6) return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3)}`;

        return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6, 10)}`;
    };

    const unformatPhoneNumber = (value: string): string => {
        return value.replace(/\D/g, '');
    };

    return {
        formatPhoneNumber,
        unformatPhoneNumber,
    };
}
