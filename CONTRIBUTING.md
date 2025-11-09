# Contributing to Interactive Giveaway System

Thank you for considering contributing to this project!

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/your-username/laravel-giveaway-system.git`
3. Install dependencies: `composer install && npm install`
4. Copy `.env.example` to `.env`
5. Generate app key: `php artisan key:generate`
6. Create database and run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Start development server: `php artisan serve`
9. Build assets: `npm run dev`

## Coding Standards

- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Write clear comments for complex logic
- Keep methods small and focused
- Use type hints

## Git Workflow

1. Create a feature branch: `git checkout -b feature/your-feature-name`
2. Make your changes
3. Commit with clear messages: `git commit -m "Add feature X"`
4. Push to your fork: `git push origin feature/your-feature-name`
5. Create a Pull Request

## Commit Message Format

```
type(scope): subject

body (optional)

footer (optional)
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

Examples:
```
feat(submissions): add email validation
fix(dashboard): correct stats calculation
docs(readme): update installation steps
```

## Testing

- Write tests for new features
- Ensure all tests pass before submitting PR
- Run tests: `php artisan test`
- Check code style: `./vendor/bin/pint`

## Pull Request Process

1. Update README.md if needed
2. Ensure all tests pass
3. Update documentation
4. Request review from maintainers
5. Address review comments
6. Squash commits if requested

## Code Review

- Be respectful and constructive
- Focus on code, not the person
- Explain your reasoning
- Be open to feedback

## Bug Reports

Include:
- Clear title and description
- Steps to reproduce
- Expected vs actual behavior
- Laravel/PHP version
- Browser/OS information
- Screenshots if applicable

## Feature Requests

- Explain the problem it solves
- Provide use cases
- Consider implementation approach
- Be open to discussion

## Questions?

Feel free to open an issue for questions or join our discussions.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
